<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAIService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Generate book description using Gemini AI
     */
    public function generateBookDescription(string $title, string $author, string $category = null): ?string
    {
        try {
            $prompt = "Generate a compelling and concise book description (2-3 sentences) for a book titled '{$title}' by {$author}";
            
            if ($category) {
                $prompt .= " in the {$category} category";
            }
            
            $prompt .= ". Make it engaging and informative.";

            $response = $this->callGeminiAPI($prompt);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Gemini AI Error (Book Description): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate library/bibliotheque description
     */
    public function generateLibraryDescription(string $name, string $city, int $capacity = null): ?string
    {
        try {
            $prompt = "Generate a professional and welcoming description (3-4 sentences) for a library named '{$name}' located in {$city}";
            
            if ($capacity) {
                $prompt .= " with a capacity of {$capacity} books";
            }
            
            $prompt .= ". Make it appealing and informative for potential visitors.";

            $response = $this->callGeminiAPI($prompt);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Gemini AI Error (Library Description): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Suggest book categories based on title and author
     */
    public function suggestBookCategory(string $title, string $author): ?string
    {
        try {
            $prompt = "Based on the book title '{$title}' by {$author}, suggest the most appropriate single category from: Fiction, Non-Fiction, Science, History, Biography, Fantasy, Mystery, Romance, Thriller, Self-Help, Technology, Art, Poetry, or Children. Return only the category name, nothing else.";

            $response = $this->callGeminiAPI($prompt);
            
            return trim($response);
        } catch (\Exception $e) {
            Log::error('Gemini AI Error (Category Suggestion): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate creative library name suggestions
     */
    public function suggestLibraryName(string $city, string $theme = null): ?array
    {
        try {
            $prompt = "Suggest 5 creative and professional names for a library in {$city}";
            
            if ($theme) {
                $prompt .= " with a {$theme} theme";
            }
            
            $prompt .= ". Return only the names, one per line, without numbering or extra text.";

            $response = $this->callGeminiAPI($prompt);
            
            if ($response) {
                $names = explode("\n", trim($response));
                return array_filter(array_map('trim', $names));
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini AI Error (Name Suggestion): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Analyze and get book recommendations
     */
    public function getBookRecommendations(array $books, int $count = 5): ?string
    {
        try {
            $bookList = collect($books)->take(10)->map(function($book) {
                return $book['title'] . ' by ' . $book['author'];
            })->implode(', ');

            $prompt = "Based on these books: {$bookList}, recommend {$count} similar books that readers might enjoy. Format as: Title by Author (one per line).";

            $response = $this->callGeminiAPI($prompt);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Gemini AI Error (Recommendations): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate book summary from title and author
     */
    public function generateBookSummary(string $title, string $author): ?string
    {
        try {
            $prompt = "Provide a brief, informative summary (4-5 sentences) of the book '{$title}' by {$author}. Include key themes and what makes it notable.";

            $response = $this->callGeminiAPI($prompt);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Gemini AI Error (Book Summary): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate image search keywords for a book
     */
    public function generateImageKeywords(string $title, string $author): ?string
    {
        try {
            $prompt = "Generate 5 relevant keywords for searching book cover images for '{$title}' by {$author}. Return as comma-separated values.";

            $response = $this->callGeminiAPI($prompt);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Gemini AI Error (Image Keywords): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate Pollinations AI image prompt based on library details
     */
    public function generateImagePrompt(string $libraryName, string $city, ?string $description = null): ?string
    {
        try {
            $prompt = "You are an AI image prompt expert. Based on this library information, generate a detailed, descriptive image prompt for Pollinations AI:\n\n";
            $prompt .= "Library Name: {$libraryName}\n";
            $prompt .= "Location: {$city}\n";
            if ($description) {
                $prompt .= "Description: {$description}\n";
            }
            $prompt .= "\nCreate a vivid, detailed image prompt (1-2 sentences) that would generate a beautiful, realistic photo of this library. ";
            $prompt .= "Focus on architecture, atmosphere, interior/exterior details, lighting, and mood. ";
            $prompt .= "Make it specific and visual. Return ONLY the image prompt, nothing else.";
            
            $imagePrompt = $this->callGeminiAPI($prompt);
            
            if ($imagePrompt) {
                return trim($imagePrompt);
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Image Prompt Generation Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate image URL using Gemini AI to create smart search keywords
     */
    public function generateLibraryImage(string $libraryName, string $city, ?string $description = null): ?string
    {
        try {
            // Use Gemini to generate smart image search keywords based on library details
            $prompt = "Based on this library information:\n";
            $prompt .= "Name: {$libraryName}\n";
            $prompt .= "Location: {$city}\n";
            if ($description) {
                $prompt .= "Description: {$description}\n";
            }
            $prompt .= "\nGenerate 3-5 relevant keywords for searching library images that match this library's character. Return only comma-separated keywords, nothing else.";
            
            $keywords = $this->callGeminiAPI($prompt);
            
            if ($keywords) {
                // Clean up keywords and create Unsplash URL
                $keywords = trim(str_replace(['"', "'", "\n"], '', $keywords));
                $encodedKeywords = urlencode($keywords);
                return "https://source.unsplash.com/800x600/?library,{$encodedKeywords}";
            }
            
            // Fallback to basic search
            $cityEncoded = urlencode($city);
            return "https://source.unsplash.com/800x600/?library,books,{$cityEncoded}";
            
        } catch (\Exception $e) {
            Log::error('Image Generation Error: ' . $e->getMessage());
            return "https://source.unsplash.com/800x600/?library,books";
        }
    }

    /**
     * Get AI-generated library image suggestions (NANO BANANA - 2 images!)
     * Uses Gemini to understand library context and find matching images
     */
    public function getLibraryImageSuggestions(string $libraryName, string $city, ?string $description = null): array
    {
        try {
            // Use Gemini to generate contextual search terms
            $prompt = "For a library called '{$libraryName}' in {$city}";
            if ($description) {
                $prompt .= " ({$description})";
            }
            $prompt .= ", generate 2 different sets of image search keywords that would represent this library well. First set for exterior/building, second for interior/atmosphere. Format: keyword1,keyword2,keyword3|keyword4,keyword5,keyword6";
            
            $keywordSets = $this->callGeminiAPI($prompt);
            
            if ($keywordSets && strpos($keywordSets, '|') !== false) {
                $sets = explode('|', $keywordSets);
                $suggestions = [];
                
                foreach (array_slice($sets, 0, 2) as $index => $keywords) {
                    $keywords = trim(str_replace(['"', "'", "\n"], '', $keywords));
                    $encoded = urlencode($keywords);
                    $suggestions[] = "https://source.unsplash.com/800x600/?library,{$encoded}&sig=" . ($index + 1);
                }
                
                return $suggestions;
            }
            
            // Fallback: Generate contextual searches without AI
            $cityEncoded = urlencode($city);
            return [
                "https://source.unsplash.com/800x600/?library,building,{$cityEncoded}&sig=1",
                "https://source.unsplash.com/800x600/?library,interior,books,{$cityEncoded}&sig=2",
            ];
            
        } catch (\Exception $e) {
            Log::error('Image Suggestions Error: ' . $e->getMessage());
            return [
                "https://source.unsplash.com/800x600/?library,architecture&sig=1",
                "https://source.unsplash.com/800x600/?library,reading,interior&sig=2",
            ];
        }
    }

    /**
     * Call the Gemini API
     */
    protected function callGeminiAPI(string $prompt): ?string
    {
        $url = $this->apiUrl . '?key=' . $this->apiKey;

        try {
            $response = Http::timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            $errorBody = $response->json();
            if (isset($errorBody['error']['code']) && $errorBody['error']['code'] == 429) {
                Log::warning('Gemini API Rate Limit Exceeded');
                return null;
            }

            Log::error('Gemini API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test API connection
     */
    public function testConnection(): array
    {
        try {
            $response = $this->callGeminiAPI('Say "Hello, the API is working!"');
            
            return [
                'success' => $response !== null,
                'message' => $response ?? 'Failed to connect to Gemini API'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}

