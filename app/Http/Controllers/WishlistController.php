<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Book;
use App\Http\Requests\WishlistRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of user's wishlist items
     */
    public function index(Request $request)
    {
        $query = Wishlist::where('user_id', Auth::id())
                        ->with(['book', 'votes']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $wishes = $query->paginate(12);

        return view('wishlist.index', compact('wishes'));
    }

    /**
     * Show the form for creating a new wishlist item
     */
    public function create()
    {
        return view('wishlist.create');
    }

    /**
     * Store a newly created wishlist item
     */
    public function store(WishlistRequest $request)
    {
        // Check for duplicates
        $existing = Wishlist::where('user_id', Auth::id())
                           ->where('title', $request->title)
                           ->where('author', $request->author)
                           ->whereIn('status', ['PENDING', 'SEARCHING', 'ORDERED'])
                           ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('warning', 'You already have a similar wish request pending!');
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('wishlist-images', 'public');
            $data['image'] = $imagePath;
        }

        Wishlist::create($data);

        return redirect()->route('wishlist.index')
            ->with('success', 'Your wish has been added! We will search for this book.');
    }

    /**
     * Display the specified wishlist item
     */
    public function show(Wishlist $wishlist)
    {
        // Ensure user can only view their own wishes
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $wishlist->load(['book', 'votes']);

        return view('wishlist.show', compact('wishlist'));
    }

    /**
     * Show the form for editing the specified wishlist item
     */
    public function edit(Wishlist $wishlist)
    {
        // Ensure user can only edit their own wishes
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only edit if pending or searching
        if (!$wishlist->canBeCancelled()) {
            return redirect()->route('wishlist.show', $wishlist)
                ->with('error', 'Cannot edit this wish request at this stage.');
        }

        return view('wishlist.edit', compact('wishlist'));
    }

    /**
     * Update the specified wishlist item
     */
    public function update(WishlistRequest $request, Wishlist $wishlist)
    {
        // Ensure user can only update their own wishes
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$wishlist->canBeCancelled()) {
            return redirect()->route('wishlist.show', $wishlist)
                ->with('error', 'Cannot update this wish request at this stage.');
        }

        $wishlist->update($request->validated());

        return redirect()->route('wishlist.show', $wishlist)
            ->with('success', 'Wish updated successfully!');
    }

    /**
     * Remove the specified wishlist item
     */
    public function destroy(Wishlist $wishlist)
    {
        // Ensure user can only delete their own wishes
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$wishlist->canBeCancelled()) {
            return redirect()->route('wishlist.index')
                ->with('error', 'Cannot cancel this wish request at this stage.');
        }

        $wishlist->delete();

        return redirect()->route('wishlist.index')
            ->with('success', 'Wish request cancelled successfully.');
    }

    /**
     * Toggle vote on a wishlist item
     */
    public function toggleVote(Wishlist $wishlist)
    {
        $voted = $wishlist->toggleVote(Auth::id());

        return response()->json([
            'success' => true,
            'voted' => $voted,
            'votes_count' => $wishlist->votes()->count()
        ]);
    }

    /**
     * Browse all public wishlist requests
     */
    public function browse(Request $request)
    {
        $query = Wishlist::with(['user', 'votes'])
                        ->withCount('votes')
                        ->where('status', '!=', 'REJECTED');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Sort by most voted
        if ($request->get('sort') === 'votes') {
            $query->orderBy('votes_count', 'desc');
        } else {
            $query->latest();
        }

        $wishes = $query->paginate(15);

        return view('wishlist.browse', compact('wishes'));
    }

    /**
     * Submit feedback and rating after book is found
     */
    public function submitFeedback(Request $request, Wishlist $wishlist)
    {
        // Ensure user owns this wish
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only rate if found
        if (!$wishlist->isFound()) {
            return redirect()->back()
                ->with('error', 'You can only rate fulfilled wishes.');
        }

        $request->validate([
            'service_rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $wishlist->update([
            'service_rating' => $request->service_rating,
            'feedback' => $request->feedback
        ]);

        return redirect()->route('wishlist.show', $wishlist)
            ->with('success', 'Thank you for your feedback!');
    }
} 