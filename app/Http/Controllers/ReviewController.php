<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Ajouter un review
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create([
            'book_id' => $request->book_id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review ajouté avec succès !');
    }

    // Mettre à jour un review
    public function update(Request $request, Review $review)
    {
        // Vérifier que c’est l’auteur
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review->update($request->only('rating', 'comment'));

        return redirect()->back()->with('success', 'Review mis à jour !');
    }

    // Supprimer un review
    public function destroy(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review supprimé !');
    }

    // Flag un review
    public function flag(Review $review)
    {
        $review->update(['is_flagged' => true]);
        return redirect()->back()->with('success', 'Review signalé !');
    }
}