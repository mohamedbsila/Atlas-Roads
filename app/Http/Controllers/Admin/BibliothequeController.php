<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bibliotheque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\GeminiAIService;

class BibliothequeController extends Controller
{
    protected $geminiAI;

    public function __construct(GeminiAIService $geminiAI)
    {
        $this->geminiAI = $geminiAI;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bibliotheque::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        // Filter by city
        if ($request->has('city') && $request->city != '') {
            $query->where('city', $request->city);
        }

        $bibliotheques = $query->withCount('books')->latest()->paginate(10);
        
        // Get unique cities for filter
        $cities = Bibliotheque::distinct()->pluck('city')->filter();
        
        // Statistics
        $stats = [
            'total' => Bibliotheque::count(),
            'active' => Bibliotheque::where('is_active', true)->count(),
            'total_books' => \App\Models\Book::whereNotNull('bibliotheque_id')->count(),
            'total_capacity' => Bibliotheque::sum('capacity'),
        ];

        return view('admin.bibliotheques.index', compact('bibliotheques', 'cities', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bibliotheques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'opening_days' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'capacity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle image upload or URL
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('bibliotheques', 'public');
        } elseif ($request->has('image_url') && $request->image_url) {
            // If AI generated image URL is provided, download and store it
            try {
                $imageContent = file_get_contents($request->image_url);
                $filename = 'bibliotheques/' . time() . '_' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $imageContent);
                $validated['image'] = $filename;
            } catch (\Exception $e) {
                // If download fails, just skip the image
                Log::warning('Failed to download AI image: ' . $e->getMessage());
            }
        }

        $bibliotheque = Bibliotheque::create($validated);

        return redirect()->route('admin.bibliotheques.index')
            ->with('success', 'Bibliothèque créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bibliotheque $bibliotheque)
    {
        $bibliotheque->load('books');
        
        // Statistics for this bibliotheque
        $stats = [
            'total_books' => $bibliotheque->books()->count(),
            'available_books' => $bibliotheque->books()->where('is_available', true)->count(),
            'borrowed_books' => $bibliotheque->books()->where('is_available', false)->count(),
            'categories' => $bibliotheque->books()->distinct('category')->count('category'),
        ];

        // Recent books
        $recentBooks = $bibliotheque->books()->latest()->take(5)->get();

        return view('admin.bibliotheques.show', compact('bibliotheque', 'stats', 'recentBooks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bibliotheque $bibliotheque)
    {
        return view('admin.bibliotheques.edit', compact('bibliotheque'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bibliotheque $bibliotheque)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'opening_days' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'capacity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($bibliotheque->image) {
                Storage::disk('public')->delete($bibliotheque->image);
            }
            $validated['image'] = $request->file('image')->store('bibliotheques', 'public');
        }

        $bibliotheque->update($validated);

        return redirect()->route('admin.bibliotheques.index')
            ->with('success', 'Bibliothèque mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bibliotheque $bibliotheque)
    {
        // Delete image if exists
        if ($bibliotheque->image) {
            Storage::disk('public')->delete($bibliotheque->image);
        }

        $bibliotheque->delete();

        return redirect()->route('admin.bibliotheques.index')
            ->with('success', 'Bibliothèque supprimée avec succès!');
    }

    /**
     * Show statistics dashboard
     */
    public function statistics()
    {
        $bibliotheques = Bibliotheque::withCount('books')->get();
        
        $stats = [
            'total_bibliotheques' => $bibliotheques->count(),
            'active_bibliotheques' => $bibliotheques->where('is_active', true)->count(),
            'total_books' => \App\Models\Book::whereNotNull('bibliotheque_id')->count(),
            'total_capacity' => $bibliotheques->sum('capacity'),
            'books_by_bibliotheque' => $bibliotheques->map(function($bib) {
                return [
                    'name' => $bib->name,
                    'books' => $bib->books_count,
                    'capacity' => $bib->capacity,
                ];
            }),
            'cities' => Bibliotheque::select('city')
                ->selectRaw('count(*) as count')
                ->groupBy('city')
                ->get(),
        ];

        return view('admin.bibliotheques.statistics', compact('stats', 'bibliotheques'));
    }

    /**
     * Generate AI description for bibliotheque
     */
    public function generateDescription(Request $request)
    {
        $name = $request->input('name');
        $city = $request->input('city');
        $capacity = $request->input('capacity');

        if (!$name || !$city) {
            return response()->json([
                'success' => false,
                'message' => 'Name and city are required'
            ], 400);
        }

        // ONLY use Gemini AI - no fallback templates
        $description = $this->geminiAI->generateLibraryDescription($name, $city, $capacity);

        if ($description) {
            return response()->json([
                'success' => true,
                'description' => $description,
                'ai_generated' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gemini API unavailable. Please try again later or enter description manually.'
        ], 200);
    }

    /**
     * Suggest library names using AI
     */
    public function suggestNames(Request $request)
    {
        $city = $request->input('city');
        $theme = $request->input('theme');

        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'City is required'
            ], 400);
        }

        // ONLY use Gemini AI - no fallback templates
        $names = $this->geminiAI->suggestLibraryName($city, $theme);

        if ($names) {
            return response()->json([
                'success' => true,
                'names' => $names,
                'ai_generated' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gemini API unavailable. Please try again later or enter name manually.'
        ], 200);
    }

    /**
     * Generate image prompt using Gemini AI for Pollinations AI
     */
    public function generateImagePrompt(Request $request)
    {
        $name = $request->input('name');
        $city = $request->input('city');
        $description = $request->input('description');

        if (!$name || !$city) {
            return response()->json([
                'success' => false,
                'message' => 'Name and city are required'
            ], 400);
        }

        $imagePrompt = $this->geminiAI->generateImagePrompt($name, $city, $description);

        if ($imagePrompt) {
            return response()->json([
                'success' => true,
                'prompt' => $imagePrompt
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate image prompt. Please try again.'
        ], 500);
    }

    /**
     * Generate image suggestions using Gemini AI
     * Gemini analyzes library details and generates smart image search keywords
     */
    public function generateImage(Request $request)
    {
        $name = $request->input('name');
        $city = $request->input('city');
        $description = $request->input('description'); // Optional description

        if (!$name || !$city) {
            return response()->json([
                'success' => false,
                'message' => 'Name and city are required'
            ], 400);
        }

        // Use Gemini AI to generate contextual image searches based on library details
        $suggestions = $this->geminiAI->getLibraryImageSuggestions($name, $city, $description);
        $mainImage = $this->geminiAI->generateLibraryImage($name, $city, $description);

        if ($mainImage && count($suggestions) > 0) {
            return response()->json([
                'success' => true,
                'image' => $mainImage,
                'suggestions' => $suggestions,
                'ai_powered' => true // Indicates Gemini AI was used for smart keyword generation
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image service unavailable. Please upload an image manually.'
        ], 200);
    }

    /**
     * Test Gemini AI connection
     */
    public function testAI()
    {
        $result = $this->geminiAI->testConnection();
        
        return response()->json($result);
    }
}
