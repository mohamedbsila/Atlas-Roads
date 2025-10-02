<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

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
        
        return view('home', compact('books', 'carouselBooks'));
    }
    
    public function show(Book $book)
    {
        // Permettre aux visiteurs de voir les détails d'un livre
        return view('books.show-public', compact('book'));
    }
}
