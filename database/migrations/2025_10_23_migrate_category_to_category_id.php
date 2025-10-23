<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Book;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all books without category_id
        $books = Book::whereNull('category_id')->get();
        
        foreach ($books as $book) {
            if ($book->category) {
                // Try to find or create a category based on the old text field
                $category = Category::firstOrCreate(
                    ['category_name' => $book->category],
                    ['description' => 'Migrated from old category system']
                );
                
                // Update the book with the category_id
                $book->update(['category_id' => $category->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set category_id to null for migrated books
        Book::whereNotNull('category_id')->update(['category_id' => null]);
    }
};

