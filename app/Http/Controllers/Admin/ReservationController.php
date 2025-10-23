<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomReservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomReservation::with(['room.section.bibliotheque', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        }

        $reservations = $query->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function confirm($id)
    {
        $reservation = RoomReservation::findOrFail($id);
        $reservation->confirm();

        return back()->with('success', 'Réservation confirmée!');
    }

    public function cancel($id)
    {
        $reservation = RoomReservation::findOrFail($id);
        $reservation->cancel();

        return back()->with('success', 'Réservation annulée!');
    }
}
