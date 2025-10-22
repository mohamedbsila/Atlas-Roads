<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\BorrowRequest;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        $myRequests = BorrowRequest::with(['book.owner'])
            ->where('borrower_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $receivedRequests = BorrowRequest::with(['book', 'borrower'])
            ->where('owner_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', compact('myRequests', 'receivedRequests'));
    }
}
