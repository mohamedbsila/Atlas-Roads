<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'cover_image',
        'story_date',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'story_date' => 'date',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


}
