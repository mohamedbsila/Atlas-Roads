<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Section;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('section.bibliotheque');

        if ($request->has('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->has('style')) {
            $query->where('style', $request->style);
        }

        $rooms = $query->paginate(15);
        $sections = Section::with('bibliotheque')->get();

        return view('admin.rooms.index', compact('rooms', 'sections'));
    }

    public function create()
    {
        $sections = Section::with('bibliotheque')->get();
        return view('admin.rooms.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'style' => 'required|in:individual,group,conference',
            'has_pc' => 'boolean',
            'has_wifi' => 'boolean',
            'equipments' => 'nullable|array',
            'price_per_hour' => 'required|numeric|min:0',
            'availability_schedule' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Salle créée avec succès!');
    }

    public function show(Room $room)
    {
        $room->load(['section.bibliotheque', 'reservations.user']);
        
        $stats = [
            'total_reservations' => $room->reservations()->count(),
            'confirmed_reservations' => $room->reservations()->where('status', 'confirmed')->count(),
            'total_revenue' => $room->reservations()->where('status', 'confirmed')->sum('total_price'),
        ];

        return view('admin.rooms.show', compact('room', 'stats'));
    }

    public function edit(Room $room)
    {
        $sections = Section::with('bibliotheque')->get();
        return view('admin.rooms.edit', compact('room', 'sections'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'style' => 'required|in:individual,group,conference',
            'has_pc' => 'boolean',
            'has_wifi' => 'boolean',
            'equipments' => 'nullable|array',
            'price_per_hour' => 'required|numeric|min:0',
            'availability_schedule' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Salle mise à jour avec succès!');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Salle supprimée avec succès!');
    }

    /**
     * Show reservations for a room
     */
    public function reservations(Room $room)
    {
        $reservations = $room->reservations()
            ->with('user')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return view('admin.rooms.reservations', compact('room', 'reservations'));
    }

    /**
     * Confirm a reservation
     */
    public function confirmReservation($roomId, $reservationId)
    {
        $reservation = \App\Models\RoomReservation::findOrFail($reservationId);
        $reservation->confirm();

        return back()->with('success', 'Réservation confirmée!');
    }

    /**
     * Cancel a reservation
     */
    public function cancelReservation($roomId, $reservationId)
    {
        $reservation = \App\Models\RoomReservation::findOrFail($reservationId);
        $reservation->cancel();

        return back()->with('success', 'Réservation annulée!');
    }
}
