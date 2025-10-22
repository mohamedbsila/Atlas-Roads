<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminWishlistController extends Controller
{
    /**
     * Display all wishlist requests for admin
     */
    public function index(Request $request)
    {
        $query = Wishlist::with(['user', 'book'])
                        ->withCount('votes');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        
        if ($sortBy === 'votes') {
            $query->orderBy('votes_count', $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $wishes = $query->paginate(20);

        // Stats
        $stats = [
            'total' => Wishlist::count(),
            'pending' => Wishlist::where('status', 'PENDING')->count(),
            'searching' => Wishlist::where('status', 'SEARCHING')->count(),
            'found' => Wishlist::where('status', 'FOUND')->count(),
            'ordered' => Wishlist::where('status', 'ORDERED')->count(),
            'rejected' => Wishlist::where('status', 'REJECTED')->count(),
        ];

        return view('admin.wishlist.index', compact('wishes', 'stats'));
    }

    /**
     * Show a specific wishlist request
     */
    public function show(Wishlist $wishlist)
    {
        $wishlist->load(['user', 'book', 'votes']);
        
        // Find similar existing books
        $similarBooks = Book::where('title', 'like', '%' . $wishlist->title . '%')
                            ->orWhere('author', 'like', '%' . $wishlist->author . '%')
                            ->limit(5)
                            ->get();

        return view('admin.wishlist.show', compact('wishlist', 'similarBooks'));
    }

    /**
     * Update wishlist status
     */
    public function updateStatus(Request $request, Wishlist $wishlist)
    {
        $request->validate([
            'status' => 'required|in:PENDING,SEARCHING,FOUND,ORDERED,REJECTED',
            'admin_notes' => 'nullable|string|max:1000',
            'estimated_price' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
        ]);

        $data = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'estimated_price' => $request->estimated_price,
            'estimated_days' => $request->estimated_days,
        ];

        // If status is FOUND, mark it
        if ($request->status === 'FOUND') {
            $data['is_found'] = true;
            $data['found_at'] = now();
        }

        $wishlist->update($data);

        return redirect()->back()
            ->with('success', 'Wishlist status updated successfully!');
    }

    /**
     * Link wishlist to an existing book
     */
    public function linkToBook(Request $request, Wishlist $wishlist)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $wishlist->markAsFound($request->book_id);

        return redirect()->back()
            ->with('success', 'Wishlist linked to book successfully!');
    }

    /**
     * Create a new book from wishlist request
     */
    public function createBook(Request $request, Wishlist $wishlist)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image_url' => 'nullable|url',
            'category' => 'required|string|max:50',
            'language' => 'required|string|max:30',
            'published_year' => 'required|integer|min:1900|max:2025',
            'is_available' => 'boolean'
        ]);

        $data = [
            'title' => $wishlist->title,
            'author' => $wishlist->author,
            'isbn' => $wishlist->isbn,
            'category' => $request->category,
            'language' => $request->language,
            'published_year' => $request->published_year,
            'is_available' => $request->has('is_available') ? true : false,
        ];

        // Handle image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $book = Book::create($data);

        // Link to wishlist
        $wishlist->markAsFound($book->id);

        return redirect()->route('admin.wishlist.show', $wishlist)
            ->with('success', 'Book created and linked successfully!');
    }

    /**
     * Dashboard with statistics
     */
    public function dashboard()
    {
        $stats = [
            'total_wishes' => Wishlist::count(),
            'pending' => Wishlist::where('status', 'PENDING')->count(),
            'searching' => Wishlist::where('status', 'SEARCHING')->count(),
            'found' => Wishlist::where('status', 'FOUND')->count(),
            'success_rate' => Wishlist::where('status', 'FOUND')->count() / max(Wishlist::count(), 1) * 100,
        ];

        $recentWishes = Wishlist::with(['user'])
                               ->latest()
                               ->limit(10)
                               ->get();

        $mostVoted = Wishlist::withCount('votes')
                            ->where('status', '!=', 'REJECTED')
                            ->orderBy('votes_count', 'desc')
                            ->limit(10)
                            ->get();

        $highPriority = Wishlist::where('priority', 'HIGH')
                               ->where('status', 'PENDING')
                               ->with(['user'])
                               ->latest()
                               ->limit(5)
                               ->get();

        return view('admin.wishlist.dashboard', compact('stats', 'recentWishes', 'mostVoted', 'highPriority'));
    }

    /**
     * Bulk update status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'wishlist_ids' => 'required|array',
            'wishlist_ids.*' => 'exists:wishlists,id',
            'status' => 'required|in:PENDING,SEARCHING,FOUND,ORDERED,REJECTED'
        ]);

        Wishlist::whereIn('id', $request->wishlist_ids)
               ->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Bulk update completed successfully!');
    }
} 