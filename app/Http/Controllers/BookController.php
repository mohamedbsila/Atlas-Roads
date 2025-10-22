<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

        // Filtre prix min / max
        if ($request->filled('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', (float) $request->price_min);
        }
        if ($request->filled('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        // Tri par prix optionnel
        if ($request->filled('sort') && in_array($request->sort, ['price_asc','price_desc'])) {
            $query->orderBy('price', $request->sort === 'price_asc' ? 'asc' : 'desc');
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

        // Assigner le propriétaire du livre au user connecté
        $data['ownerId'] = Auth::id();

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Book has been successfully added!');
    }

    public function show(Book $book)
    {
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
            if ($book->image && !str_starts_with($book->image, 'http') && Storage::exists($book->image)) {
                Storage::delete($book->image);
            }
            $data['image'] = $request->file('image')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            // Delete old image if it's a local file
            if ($book->image && !str_starts_with($book->image, 'http') && Storage::exists($book->image)) {
                Storage::delete($book->image);
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
}

