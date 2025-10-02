<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'isbn',
        'description',
        'image',
        'priority',
        'status',
        'admin_notes',
        'book_id',
        'is_found',
        'found_at',
        'estimated_price',
        'estimated_days',
        'max_price',
        'service_rating',
        'feedback'
    ];

    protected $casts = [
        'is_found' => 'boolean',
        'found_at' => 'datetime',
        'estimated_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'service_rating' => 'integer',
        'estimated_days' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlist_votes')
                    ->withTimestamps();
    }

    // Helper methods
    public function getVotesCountAttribute()
    {
        return $this->votes()->count();
    }

    public function hasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function toggleVote($userId)
    {
        if ($this->hasVoted($userId)) {
            $this->votes()->detach($userId);
            return false;
        } else {
            $this->votes()->attach($userId);
            return true;
        }
    }

    // Status helpers
    public function isPending()
    {
        return $this->status === 'PENDING';
    }

    public function isSearching()
    {
        return $this->status === 'SEARCHING';
    }

    public function isFound()
    {
        return $this->status === 'FOUND' || $this->is_found;
    }

    public function isOrdered()
    {
        return $this->status === 'ORDERED';
    }

    public function isRejected()
    {
        return $this->status === 'REJECTED';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['PENDING', 'SEARCHING']);
    }

    public function markAsFound($bookId = null)
    {
        $this->update([
            'status' => 'FOUND',
            'is_found' => true,
            'found_at' => now(),
            'book_id' => $bookId
        ]);
    }

    // Status badge colors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'PENDING' => 'gray',
            'SEARCHING' => 'blue',
            'FOUND' => 'green',
            'ORDERED' => 'purple',
            'REJECTED' => 'red',
            default => 'gray'
        };
    }

    // Status labels
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'PENDING' => 'Pending',
            'SEARCHING' => 'Searching',
            'FOUND' => 'Found',
            'ORDERED' => 'Ordered',
            'REJECTED' => 'Rejected',
            default => $this->status
        };
    }

    // Priority labels
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'HIGH' => 'High',
            'MEDIUM' => 'Medium',
            'LOW' => 'Low',
            default => $this->priority
        };
    }

    // Priority colors
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'HIGH' => 'red',
            'MEDIUM' => 'yellow',
            'LOW' => 'green',
            default => 'gray'
        };
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeSearching($query)
    {
        return $query->where('status', 'SEARCHING');
    }

    public function scopeFound($query)
    {
        return $query->where('status', 'FOUND');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeMostVoted($query)
    {
        return $query->withCount('votes')->orderBy('votes_count', 'desc');
    }
} 