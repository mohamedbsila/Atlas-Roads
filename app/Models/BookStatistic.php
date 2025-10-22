<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookStatistic extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'book_id',
        'average_rating',
        'total_reviews',
        'total_recommendations',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
