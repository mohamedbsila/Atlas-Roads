<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'title',
        'agenda',
        'scheduled_at',
        'location',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Club, Meeting>
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}


