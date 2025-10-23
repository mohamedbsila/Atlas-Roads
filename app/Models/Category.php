<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'category_name',
        'description',
        'book_count'
    ];

    protected $casts = [
        'book_count' => 'integer',
    ];

    /**
     * Boot method to add model event listeners
     */
    protected static function booted(): void
    {
        // When a category is deleted, also delete all related books
        static::deleting(function (Category $category) {
            $category->books()->delete();
        });
    }

    /**
     * Get the books for the category
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Increment book count
     */
    public function incrementBookCount()
    {
        $this->increment('book_count');
    }

    /**
     * Decrement book count
     */
    public function decrementBookCount()
    {
        if ($this->book_count > 0) {
            $this->decrement('book_count');
        }
    }
}
