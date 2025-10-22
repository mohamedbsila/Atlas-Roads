<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Bibliotheque;
use Illuminate\Support\Collection;

class RoomRecommendationService
{
    /**
     * Recommend rooms based on user preferences and location
     */
    public function recommend(array $filters): Collection
    {
        $query = Room::with(['section.bibliotheque'])
            ->where('is_active', true);

        // Filter by style (individual, group, conference)
        if (!empty($filters['style'])) {
            $query->where('style', $filters['style']);
        }

        // Filter by capacity
        if (!empty($filters['capacity'])) {
            $query->where('capacity', '>=', $filters['capacity']);
        }

        // Filter by PC requirement
        if (!empty($filters['has_pc']) && $filters['has_pc']) {
            $query->where('has_pc', true);
        }

        // Filter by WiFi requirement
        if (!empty($filters['has_wifi']) && $filters['has_wifi']) {
            $query->where('has_wifi', true);
        }

        // Filter by equipments
        if (!empty($filters['equipments'])) {
            foreach ($filters['equipments'] as $equipment) {
                $query->whereJsonContains('equipments', $equipment);
            }
        }

        // Filter by price range
        if (!empty($filters['max_price'])) {
            $query->where('price_per_hour', '<=', $filters['max_price']);
        }

        // Check availability for specific date/time
        if (!empty($filters['date']) && !empty($filters['start_time']) && !empty($filters['end_time'])) {
            $query->whereDoesntHave('reservations', function($q) use ($filters) {
                $q->where('date', $filters['date'])
                  ->whereIn('status', ['pending', 'confirmed'])
                  ->where(function($subQ) use ($filters) {
                      $subQ->where('start_time', '<', $filters['end_time'])
                           ->where('end_time', '>', $filters['start_time']);
                  });
            });
        }

        $rooms = $query->get();

        // Sort by location if user coordinates provided
        if (!empty($filters['user_lat']) && !empty($filters['user_lng'])) {
            $rooms = $this->sortByDistance($rooms, $filters['user_lat'], $filters['user_lng']);
        }

        // Add AI scoring
        return $this->scoreAndRank($rooms, $filters);
    }

    /**
     * Sort rooms by distance from user location
     */
    protected function sortByDistance(Collection $rooms, float $userLat, float $userLng): Collection
    {
        return $rooms->map(function($room) use ($userLat, $userLng) {
            $bibliotheque = $room->section->bibliotheque;
            
            if ($bibliotheque->latitude && $bibliotheque->longitude) {
                $room->distance = $this->calculateDistance(
                    $userLat, 
                    $userLng, 
                    $bibliotheque->latitude, 
                    $bibliotheque->longitude
                );
            } else {
                $room->distance = 9999; // Unknown distance
            }
            
            return $room;
        })->sortBy('distance');
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Score and rank rooms based on AI logic
     */
    protected function scoreAndRank(Collection $rooms, array $filters): Collection
    {
        return $rooms->map(function($room) use ($filters) {
            $score = 100;

            // Distance score (closer is better)
            if (isset($room->distance)) {
                if ($room->distance < 1) {
                    $score += 20;
                } elseif ($room->distance < 5) {
                    $score += 10;
                } elseif ($room->distance > 20) {
                    $score -= 20;
                }
            }

            // Price score (cheaper is better, but not too cheap)
            if ($room->price_per_hour == 0) {
                $score += 30; // Free rooms are great
            } elseif ($room->price_per_hour < 10) {
                $score += 15;
            } elseif ($room->price_per_hour > 50) {
                $score -= 10;
            }

            // Capacity match score
            if (!empty($filters['capacity'])) {
                $capacityDiff = abs($room->capacity - $filters['capacity']);
                if ($capacityDiff == 0) {
                    $score += 15; // Perfect match
                } elseif ($capacityDiff <= 2) {
                    $score += 5; // Close match
                }
            }

            // Equipment bonus
            if ($room->has_pc) $score += 5;
            if ($room->has_wifi) $score += 5;
            if (!empty($room->equipments)) {
                $score += count($room->equipments) * 2;
            }

            // Style preference
            if (!empty($filters['style']) && $room->style === $filters['style']) {
                $score += 10;
            }

            $room->ai_score = $score;
            return $room;
        })->sortByDesc('ai_score');
    }

    /**
     * Find optimal time slot for a room
     */
    public function suggestOptimalTimeSlot(Room $room, string $date, int $durationHours = 2): ?array
    {
        $businessHours = [
            'start' => '08:00',
            'end' => '20:00'
        ];

        $availableSlots = [];
        $currentTime = $businessHours['start'];

        while ($currentTime < $businessHours['end']) {
            $endTime = date('H:i', strtotime($currentTime) + ($durationHours * 3600));
            
            if ($endTime > $businessHours['end']) {
                break;
            }

            if ($room->isAvailable($date, $currentTime, $endTime)) {
                $availableSlots[] = [
                    'start_time' => $currentTime,
                    'end_time' => $endTime,
                    'price' => $room->calculatePrice($currentTime, $endTime)
                ];
            }

            // Move to next hour
            $currentTime = date('H:i', strtotime($currentTime) + 3600);
        }

        return !empty($availableSlots) ? $availableSlots[0] : null;
    }
}

