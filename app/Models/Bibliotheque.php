<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bibliotheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'postal_code',
        'phone',
        'email',
        'website',
        'opening_time',
        'closing_time',
        'opening_days',
        'image',
        'capacity',
        'is_active',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'opening_days' => 'array',
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationship with books
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Scope for active bibliotheques
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get formatted opening hours
    public function getFormattedOpeningHoursAttribute()
    {
        if ($this->opening_time && $this->closing_time) {
            return date('H:i', strtotime($this->opening_time)) . ' - ' . date('H:i', strtotime($this->closing_time));
        }
        return 'Not specified';
    }

    // Get total books count
    public function getTotalBooksAttribute()
    {
        return $this->books()->count();
    }

    // Get available books count
    public function getAvailableBooksAttribute()
    {
        return $this->books()->where('is_available', true)->count();
    }
}
