<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Book;
use App\Models\User;
use App\Enums\RequestStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les demandes selon le rôle de l'utilisateur
        $myRequests = BorrowRequest::with(['book', 'owner'])
            ->where('borrower_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $receivedRequests = BorrowRequest::with(['book', 'borrower'])
            ->where('owner_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('borrow-requests.index', compact('myRequests', 'receivedRequests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500'
        ]);

        $book = Book::findOrFail($request->book_id);
        
        // Vérifier que l'utilisateur ne demande pas son propre livre
        if ($book->ownerId == Auth::id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas emprunter votre propre livre.']);
        }

        // Vérifier s'il y a déjà une demande active pour ce livre
        $existingRequest = BorrowRequest::where('book_id', $book->id)
            ->where('borrower_id', Auth::id())
            ->whereIn('status', [RequestStatus::PENDING, RequestStatus::APPROVED])
            ->exists();

        if ($existingRequest) {
            return back()->withErrors(['error' => 'Vous avez déjà une demande active pour ce livre.']);
        }

        BorrowRequest::createRequest(
            Auth::id(),
            $book->id,
            Carbon::parse($request->start_date),
            Carbon::parse($request->end_date),
            $request->notes
        );

        return back()->with('success', 'Demande d\'emprunt envoyée avec succès !');
    }

    /**
     * Approuver une demande d'emprunt
     */
    public function approve(BorrowRequest $borrowRequest)
    {
        // Vérifier que l'utilisateur connecté est le propriétaire
        if ($borrowRequest->owner_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.']);
        }

        if ($borrowRequest->approveRequest()) {
            // Mettre à jour la disponibilité du livre
            $borrowRequest->book->update(['is_available' => false]);
            
            return back()->with('success', 'Demande approuvée avec succès !');
        }

        return back()->withErrors(['error' => 'Impossible d\'approuver cette demande.']);
    }

    /**
     * Rejeter une demande d'emprunt
     */
    public function reject(BorrowRequest $borrowRequest)
    {
        // Vérifier que l'utilisateur connecté est le propriétaire
        if ($borrowRequest->owner_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.']);
        }

        if ($borrowRequest->rejectRequest()) {
            return back()->with('success', 'Demande rejetée.');
        }

        return back()->withErrors(['error' => 'Impossible de rejeter cette demande.']);
    }

    /**
     * Marquer une demande comme retournée
     */
    public function markAsReturned(BorrowRequest $borrowRequest)
    {
        // Vérifier que l'utilisateur connecté est le propriétaire
        if ($borrowRequest->owner_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.']);
        }

        if ($borrowRequest->markAsReturned()) {
            // Remettre le livre comme disponible
            $borrowRequest->book->update(['is_available' => true]);
            
            return back()->with('success', 'Livre marqué comme retourné !');
        }

        return back()->withErrors(['error' => 'Impossible de marquer cette demande comme retournée.']);
    }

    /**
     * Cancel a borrow request (by borrower)
     */
    public function cancel(BorrowRequest $borrowRequest)
    {
        // Vérifier que l'utilisateur connecté est l'emprunteur
        if ($borrowRequest->borrower_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.']);
        }

        // On peut seulement annuler si c'est en attente
        if ($borrowRequest->status === RequestStatus::PENDING) {
            $borrowRequest->rejectRequest(); // On utilise reject pour changer le statut
            return back()->with('success', 'Demande annulée.');
        }

        return back()->withErrors(['error' => 'Impossible d\'annuler cette demande.']);
    }
}
