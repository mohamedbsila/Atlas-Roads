<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'message',
    ];

    // 🔗 Relation : chaque recommandation appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Relation : chaque recommandation concerne un livre
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
