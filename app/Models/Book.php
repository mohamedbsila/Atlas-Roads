<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use HasFactory;

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
        'is_available',
        'ownerId'
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

    /**
     * Propriétaire du livre
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ownerId');
    }

    /**
     * Demandes d'emprunt pour ce livre
     */
    public function borrowRequests(): HasMany
    {
        return $this->hasMany(BorrowRequest::class);
    }

    /**
     * Demandes d'emprunt actives (approuvées) pour ce livre
     */
    public function activeBorrowRequests(): HasMany
    {
        return $this->hasMany(BorrowRequest::class)->where('status', 'approved');
    }

    /**
     * Reviews pour ce livre
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Vérifier si le livre est actuellement emprunté
     */
    public function isCurrentlyBorrowed(): bool
    {
        return $this->activeBorrowRequests()->exists();
    }

    /**
     * Obtenir l'emprunt actuel s'il existe
     */
    public function getCurrentBorrow()
    {
        return $this->activeBorrowRequests()->first();
    }

    /**
     * Vérifier si le livre est disponible
     */
    public function isAvailable(): bool
    {
        return (bool) $this->is_available;
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
    public function recommendations()
{
    return $this->hasMany(Recommendation::class);
}
public function statistics()
{
    return $this->hasOne(BookStatistic::class);
}


 
}