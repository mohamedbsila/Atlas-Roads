<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'club_id',
        'status',
        'message',
        'admin_notes',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, ClubMembership>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Club, ClubMembership>
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
