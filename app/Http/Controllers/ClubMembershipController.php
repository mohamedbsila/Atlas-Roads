<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Club;
use App\Models\ClubMembership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClubJoinRequestMail;
use Illuminate\Support\Facades\Log;

class ClubMembershipController extends Controller
{
    public function store(Request $request, Club $club): RedirectResponse
{
    if (!Auth::check()) {
        return back()->with('error', 'You must be logged in to join a club.');
    }

    $request->validate([
        'message' => ['nullable', 'string', 'max:500'],
    ]);

    $existingMembership = ClubMembership::where('user_id', Auth::id())
        ->where('club_id', $club->id)
        ->first();

    if ($existingMembership) {
        return back()->with('error', 'You have already applied to join this club.');
    }

    ClubMembership::create([
        'user_id' => Auth::id(),
        'club_id' => $club->id,
        'message' => $request->message,
        'status' => 'pending',
    ]);

    $recipient = 'nourhen.ouhichi@gmail.com';
    try {
        Log::info('Sending ClubJoinRequestMail to ' . $recipient . ' for user ID ' . Auth::id());
        Mail::to($recipient)->send(new ClubJoinRequestMail(Auth::user(), $club, $request->message));
        Log::info('Mail sent successfully.');
    } catch (\Throwable $e) {
        Log::error('Failed to send Club join request email', [
            'error' => $e->getMessage(),
            'club_id' => $club->id,
            'user_id' => Auth::id(),
        ]);
    }

    return back()->with('success', 'Your application has been submitted successfully!');
}


    public function index(): View
    {
        $memberships = ClubMembership::with(['club', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.club-memberships.index', compact('memberships'));
    }

    public function approve(ClubMembership $membership): RedirectResponse
    {
        $membership->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Membership application approved successfully!');
    }

    public function reject(Request $request, ClubMembership $membership): RedirectResponse
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $membership->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Membership application rejected.');
    }
}

