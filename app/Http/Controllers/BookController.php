<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('year')) {
            $query->where('published_year', $request->year);
        }

        if ($request->filled('available')) {
            $query->where('is_available', $request->available === '1');
        }

        $books = $query->latest()->paginate(15);
        $categories = Book::distinct()->pluck('category')->sort();
        $languages = Book::distinct()->pluck('language')->sort();
        $years = Book::distinct()->pluck('published_year')->sortDesc();

        return view('books.index', compact('books', 'categories', 'languages', 'years'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(BookRequest $request)
    {
        $data = $request->validated();

        // Handle image (uploaded file or external URL)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $data['is_available'] = $request->has('is_available') ? true : false;
        
        // Automatically assign the logged-in user as the owner
        $data['ownerId'] = auth()->id();

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Book has been successfully added!');
    }

    public function show(Book $book)
    {
        // Load reviews with user data
        $book->load('reviews.user');
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(BookRequest $request, Book $book)
    {
        $data = $request->validated();

        // Handle image (uploaded file or external URL)
        if ($request->hasFile('image')) {
            // Delete old image if it's a local file
            if ($book->image && !str_starts_with($book->image, 'http') && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
            $data['image'] = $request->file('image')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            // Delete old image if it's a local file
            if ($book->image && !str_starts_with($book->image, 'http') && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
            $data['image'] = $request->image_url;
        }

        $data['is_available'] = $request->has('is_available') ? true : false;

        $book->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Book has been successfully updated!');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book has been successfully deleted!');
    }

    /**
     * Download book details as PDF
     */
    public function downloadPdf(Book $book)
    {
        $pdf = \PDF::loadView('books.pdf', compact('book'));
        
        $filename = 'book_' . \Str::slug($book->title) . '_' . time() . '.pdf';
        
        return $pdf->download($filename);
    }
}

