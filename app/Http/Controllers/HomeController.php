<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Event;
use App\Models\Club;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les 6 premiers livres pour le carousel
        $carouselBooks = Book::where('is_available', true)
                            ->orderBy('created_at', 'desc')
                            ->limit(6)
                            ->get();
        
        // Récupérer tous les livres disponibles avec pagination pour la grille
        $books = Book::where('is_available', true)
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
        
        // Load latest public events that have a thumbnail
        $events = Event::where('is_public', true)
            ->whereNotNull('thumbnail')
            ->orderBy('start_date', 'desc')
            ->take(6)
            ->get();

        // Load clubs with their meetings for the frontend
        $clubs = Club::with(['meetings' => function ($query) {
            $query->orderBy('scheduled_at', 'desc')->take(3);
        }])
        ->withCount('meetings')
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();

        return view('home', compact('books', 'carouselBooks', 'events', 'clubs'));
    }
    
    public function show(Book $book)
    {
        // Permettre aux visiteurs de voir les détails d'un livre
        return view('books.show-public', compact('book'));
    }
}
