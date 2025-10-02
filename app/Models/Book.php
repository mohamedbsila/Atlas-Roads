<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $fillable = [
        'image',
        'title',
        'author',
        'isbn',
        'category',
        'language',
        'published_year',
        'is_available'
    ];

    protected $casts = [
        'published_year' => 'integer',
        'is_available' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        // Si l'image est une URL externe (http/https)
        if ($this->image && (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://'))) {
            return $this->image;
        }
        
        // Si l'image est un fichier local dans storage
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        
        // Images par défaut variées selon l'ID du livre
        $defaultImages = [
            'curved0.jpg',
            'curved1.jpg', 
            'curved6.jpg',
            'curved8.jpg',
            'curved14.jpg'
        ];
        
        $index = $this->id ? ($this->id - 1) % count($defaultImages) : 0;
        return asset('assets/img/curved-images/' . $defaultImages[$index]);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($book) {
            if ($book->image && Storage::exists($book->image)) {
                Storage::delete($book->image);
            }
        });
    }
}
