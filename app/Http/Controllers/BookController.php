<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

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
        $categories = \App\Models\Category::orderBy('category_name')->get();
        return view('books.create', compact('categories'));
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
        
        // Convert empty string to null for category_id
        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }
        
        // Automatically assign the logged-in user as the owner
        $data['ownerId'] = auth()->id();

        $book = Book::create($data);

        // Update category book_count
        if ($book->category_id) {
            $category = \App\Models\Category::find($book->category_id);
            if ($category) {
                $category->incrementBookCount();
            }
        }

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
        $categories = \App\Models\Category::orderBy('category_name')->get();
        return view('books.edit', compact('book', 'categories'));
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

        // Convert empty string to null for category_id
        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }

        // Track category change
        $oldCategoryId = $book->category_id;
        $newCategoryId = $data['category_id'] ?? null;

        $book->update($data);

        // Update book_count for categories
        if ($oldCategoryId != $newCategoryId) {
            // Decrement old category
            if ($oldCategoryId) {
                $oldCategory = \App\Models\Category::find($oldCategoryId);
                if ($oldCategory) {
                    $oldCategory->decrementBookCount();
                }
            }
            // Increment new category
            if ($newCategoryId) {
                $newCategory = \App\Models\Category::find($newCategoryId);
                if ($newCategory) {
                    $newCategory->incrementBookCount();
                }
            }
        }

        return redirect()->route('books.index')
            ->with('success', 'Book has been successfully updated!');
    }

    public function destroy(Book $book)
    {
        // Decrement category book_count
        if ($book->category_id) {
            $category = \App\Models\Category::find($book->category_id);
            if ($category) {
                $category->decrementBookCount();
            }
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book has been successfully deleted!');
    }

    /**
     * Download book information as PDF
     */
    public function downloadPdf(Book $book)
    {
        // Eager load the category relationship
        $book->load('category');

        // Generate PDF
        $pdf = Pdf::loadView('books.pdf', compact('book'));
        
        // Sanitize filename
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $book->title) . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
    }
}

