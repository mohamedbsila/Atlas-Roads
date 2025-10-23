<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Room extends Model
{
    protected $fillable = [
        'section_id',
        'name',
        'capacity',
        'style',
        'has_pc',
        'has_wifi',
        'equipments',
        'price_per_hour',
        'availability_schedule',
        'is_active',
    ];

    protected $casts = [
        'has_pc' => 'boolean',
        'has_wifi' => 'boolean',
        'equipments' => 'array',
        'availability_schedule' => 'array',
        'is_active' => 'boolean',
        'price_per_hour' => 'decimal:2',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(RoomReservation::class);
    }

    public function activeReservations(): HasMany
    {
        return $this->hasMany(RoomReservation::class)
            ->whereIn('status', ['pending', 'confirmed']);
    }

    /**
     * Check if room is available at given date and time
     */
    public function isAvailable(string $date, string $startTime, string $endTime): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check for overlapping reservations
        return !$this->reservations()
            ->where('date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($query) use ($startTime, $endTime) {
                $query->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
    }

    /**
     * Calculate price based on duration
     */
    public function calculatePrice(string $startTime, string $endTime): float
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $hours = $end->diffInHours($start);
        $minutes = $end->diffInMinutes($start) % 60;
        
        $totalHours = $hours + ($minutes / 60);
        
        return round($this->price_per_hour * $totalHours, 2);
    }

    /**
     * Get bibliotheque through section
     */
    public function getBibliotheque()
    {
        return $this->section->bibliotheque;
    }
}
