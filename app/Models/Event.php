<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'organizer_id', 'title', 'description', 'location', 'thumbnail', 'start_date', 'end_date', 'max_participants', 'is_public', 'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getThumbnailUrlAttribute(): string
    {
        $image = $this->thumbnail;

        // External URL
        if ($image && (str_starts_with($image, 'http://') || str_starts_with($image, 'https://'))) {
            return $image;
        }

        // Local storage file
        if ($image && Storage::disk('public')->exists($image)) {
            return asset('storage/' . $image);
        }

        // Fallback image
        return asset('assets/img/curved-images/curved14.jpg');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class)
            ->withTimestamps();
    }

        public static function createWithCommunities(array $attributes, array $communityIds = []): self
        {
            $event = static::create($attributes);
            if (!empty($communityIds)) {
                $event->communities()->attach($communityIds);
            }
            return $event;
        }

        public function deleteWithCommunities(): bool
        {
            $this->communities()->detach();
            return $this->delete();
        }
}
