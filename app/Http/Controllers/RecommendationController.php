<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recommendation;
use App\Models\Book;
use App\Services\BookStatisticService;

class RecommendationController extends Controller
{
    protected $bookStats;

    public function __construct(BookStatisticService $bookStats)
    {
        $this->bookStats = $bookStats;
    }

    /**
     * Enregistrer une nouvelle recommandation
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'message' => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($request->book_id); // <- récupère l'objet Book

        Recommendation::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'message' => $request->message,
        ]);

        // Mise à jour des statistiques du livre via l'objet Book
        $this->bookStats->updateBookStats($book, ['views' => $book->views + 1]);

        return back()->with('success', 'Livre recommandé avec succès 🎉');
    }

    /**
     * Modifier une recommandation existante
     */
    public function update(Request $request, Recommendation $recommendation)
    {
        if ($recommendation->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        $recommendation->update(['message' => $request->message]);

        $book = Book::findOrFail($recommendation->book_id);
        $this->bookStats->updateBookStats($book, ['views' => $book->views + 1]);

        return back()->with('success', 'Recommandation mise à jour ✔️');
    }

    /**
     * Supprimer une recommandation
     */
    public function destroy(Recommendation $recommendation)
    {
        if ($recommendation->user_id !== auth()->id()) {
            abort(403);
        }

        $book = Book::findOrFail($recommendation->book_id);

        $recommendation->delete();

        $this->bookStats->updateBookStats($book, ['views' => $book->views + 1]);

        return back()->with('success', 'Recommandation supprimée ❌');
    }
}
