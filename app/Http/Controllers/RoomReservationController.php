<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomReservation;
use App\Services\RoomRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomReservationController extends Controller
{
    protected $recommendationService;

    public function __construct(RoomRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Search and recommend rooms
     */
    public function search(Request $request)
    {
        $filters = $request->only([
            'style', 'capacity', 'has_pc', 'has_wifi', 
            'equipments', 'max_price', 'date', 'start_time', 
            'end_time', 'user_lat', 'user_lng'
        ]);

        $rooms = $this->recommendationService->recommend($filters);

        return view('rooms.search', compact('rooms', 'filters'));
    }

    /**
     * Show room details
     */
    public function show(Room $room)
    {
        $room->load(['section.bibliotheque']);
        
        return view('rooms.show', compact('room'));
    }

    /**
     * Show reservation form
     */
    public function reserve(Room $room)
    {
        return view('rooms.reserve', compact('room'));
    }

    /**
     * Store reservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            // Use database transaction with locking to prevent race conditions
            $reservation = \DB::transaction(function () use ($validated) {
                // Lock the room row to prevent concurrent bookings
                $room = Room::lockForUpdate()->findOrFail($validated['room_id']);

                // Check if room is active
                if (!$room->is_active) {
                    throw new \Exception('Cette salle n\'est pas active.');
                }

                // Check for overlapping reservations (double check inside transaction)
                $hasConflict = RoomReservation::where('room_id', $validated['room_id'])
                    ->where('date', $validated['date'])
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function($query) use ($validated) {
                        $query->where(function($q) use ($validated) {
                            $q->where('start_time', '<', $validated['end_time'])
                              ->where('end_time', '>', $validated['start_time']);
                        });
                    })
                    ->exists();

                if ($hasConflict) {
                    throw new \Exception('Désolé, cette salle est déjà réservée pour ce créneau horaire. Veuillez choisir un autre horaire.');
                }

                // Calculate price
                $totalPrice = $room->calculatePrice($validated['start_time'], $validated['end_time']);

                // Create reservation
                $reservation = RoomReservation::create([
                    'room_id' => $validated['room_id'],
                    'user_id' => Auth::id(),
                    'date' => $validated['date'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                return $reservation;
            });

            return redirect()->route('room-reservations.my-reservations')
                ->with('success', 'Réservation créée avec succès! En attente de confirmation. Prix total: ' . number_format($reservation->total_price, 2) . '€');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show user's reservations
     */
    public function myReservations()
    {
        $reservations = RoomReservation::with(['room.section.bibliotheque'])
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('rooms.my-reservations', compact('reservations'));
    }

    /**
     * Cancel reservation
     */
    public function cancel(RoomReservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status == 'cancelled') {
            return back()->with('error', 'Cette réservation est déjà annulée.');
        }

        // Cancel the reservation (sets status to 'cancelled', does NOT delete)
        $reservation->cancel();

        return back()->with('success', 'Réservation annulée avec succès. Le créneau horaire est maintenant disponible pour d\'autres utilisateurs.');
    }

    /**
     * Check availability (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $room = Room::findOrFail($request->room_id);
        
        $isAvailable = $room->isAvailable(
            $request->date,
            $request->start_time,
            $request->end_time
        );

        $price = $room->calculatePrice($request->start_time, $request->end_time);

        return response()->json([
            'available' => $isAvailable,
            'price' => $price,
            'message' => $isAvailable 
                ? 'Disponible - Prix: ' . $price . '€' 
                : 'Non disponible pour ce créneau'
        ]);
    }

    /**
     * Get optimal time suggestions (AJAX)
     */
    public function suggestTimes(Request $request)
    {
        $room = Room::findOrFail($request->room_id);
        $date = $request->date;
        $duration = $request->duration ?? 2;

        $suggestion = $this->recommendationService->suggestOptimalTimeSlot($room, $date, $duration);

        return response()->json([
            'success' => $suggestion !== null,
            'suggestion' => $suggestion
        ]);
    }
}
