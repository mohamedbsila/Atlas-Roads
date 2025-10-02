<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\User;
use App\Models\Book;
use App\Enums\RequestStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowRequestTestController extends Controller
{
    public function test()
    {
        $output = [];
        
        $output[] = "=== TEST DE LA CLASSE BORROW REQUEST ===";
        $output[] = "";
        
        // Test 1: Statistiques générales
        $output[] = "1. STATISTIQUES GÉNÉRALES:";
        $output[] = "- Total demandes: " . BorrowRequest::count();
        $output[] = "- Demandes en attente: " . BorrowRequest::where('status', RequestStatus::PENDING)->count();
        $output[] = "- Demandes approuvées: " . BorrowRequest::where('status', RequestStatus::APPROVED)->count();
        $output[] = "- Demandes retournées: " . BorrowRequest::where('status', RequestStatus::RETURNED)->count();
        $output[] = "";

        // Test 2: Relations
        $output[] = "2. TEST DES RELATIONS:";
        $request = BorrowRequest::with(['borrower', 'owner', 'book'])->first();
        if ($request) {
            $output[] = "- Demande ID: " . $request->id;
            $output[] = "- Emprunteur: " . $request->borrower->name;
            $output[] = "- Propriétaire: " . $request->owner->name;
            $output[] = "- Livre: " . $request->book->title;
            $output[] = "- Statut: " . $request->status->label();
            $output[] = "- Durée: " . $request->getDurationInDays() . " jours";
        }
        $output[] = "";

        // Test 3: Méthodes de la classe
        $output[] = "3. TEST DES MÉTHODES:";
        $pending = BorrowRequest::where('status', RequestStatus::PENDING)->first();
        if ($pending) {
            $output[] = "- Demande en attente trouvée (ID: " . $pending->id . ")";
            $output[] = "- isPending(): " . ($pending->isPending() ? 'Oui' : 'Non');
            $output[] = "- isActive(): " . ($pending->isActive() ? 'Oui' : 'Non');
            $output[] = "- isCompleted(): " . ($pending->isCompleted() ? 'Oui' : 'Non');
        } else {
            $output[] = "- Aucune demande en attente trouvée";
        }
        $output[] = "";

        // Test 4: Relations dans les autres modèles
        $output[] = "4. TEST DES RELATIONS DANS LES AUTRES MODÈLES:";
        $user = User::with(['borrowRequests', 'receivedBorrowRequests'])->first();
        if ($user) {
            $output[] = "- Utilisateur: " . $user->name;
            $output[] = "- Ses demandes d'emprunt: " . $user->borrowRequests->count();
            $output[] = "- Demandes reçues: " . $user->receivedBorrowRequests->count();
        }
        
        $book = Book::with(['borrowRequests'])->first();
        if ($book) {
            $output[] = "- Livre: " . $book->title;
            $output[] = "- Demandes pour ce livre: " . $book->borrowRequests->count();
            $output[] = "- Actuellement emprunté: " . ($book->isCurrentlyBorrowed() ? 'Oui' : 'Non');
        }
        $output[] = "";

        // Test 5: Créer une nouvelle demande
        $output[] = "5. TEST CRÉATION D'UNE NOUVELLE DEMANDE:";
        $users = User::all();
        $books = Book::all();

        if ($users->count() >= 2 && $books->count() >= 1) {
            $borrower = $users->where('id', '!=', $books->first()->ownerId)->first();
            if ($borrower) {
                $book = $books->first();
                
                $newRequest = BorrowRequest::createRequest(
                    $borrower->id,
                    $book->id,
                    Carbon::now()->addDays(1),
                    Carbon::now()->addDays(14),
                    'Demande créée via le contrôleur de test'
                );
                
                $output[] = "- Nouvelle demande créée (ID: " . $newRequest->id . ")";
                $output[] = "- Emprunteur: " . $newRequest->borrower->name;
                $output[] = "- Livre: " . $newRequest->book->title;
                $output[] = "- Statut: " . $newRequest->status->label();
            }
        }

        $output[] = "";
        $output[] = "=== FIN DU TEST ===";
        
        return response('<pre>' . implode("\n", $output) . '</pre>');
    }
}
