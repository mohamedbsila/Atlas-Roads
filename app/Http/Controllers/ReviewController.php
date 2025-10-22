<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\BookStatisticService;

class ReviewController extends Controller
{
    protected $bookStats;

    public function __construct(BookStatisticService $bookStats)
    {
        $this->bookStats = $bookStats;
    }

    // Ajouter un review
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $book = Book::findOrFail($request->book_id);

        Review::create([
            'book_id' => $book->id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Mettre à jour les statistiques du livre
        $this->bookStats->updateBookStats($book, ['views' => $book->views + 1]);

        return redirect()->back()->with('success', 'Review ajouté avec succès !');
    }

    // Mettre à jour un review
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review->update($request->only('rating', 'comment'));

        // Mettre à jour les statistiques du livre associé
        $book = $review->book;
        $this->bookStats->updateBookStats($book, ['views' => $book->views + 1]);

        return redirect()->back()->with('success', 'Review mis à jour !');
    }

    // Supprimer un review
    public function destroy(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $book = $review->book; // récupérer le livre associé avant suppression
        $review->delete();

        $this->bookStats->updateBookStats($book, ['views' => $book->views + 1]);

        return redirect()->back()->with('success', 'Review supprimé !');
    }

    // Flag un review
    public function flag(Review $review)
    {
        $review->update(['is_flagged' => true]);
        return redirect()->back()->with('success', 'Review signalé !');
    }
}
