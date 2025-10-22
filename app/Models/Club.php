<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'image',
    ];

    /**
     * @return HasMany<Meeting>
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * @return HasMany<ClubMembership>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(ClubMembership::class);
    }

    /**
     * @return HasMany<ClubMembership>
     */
    public function pendingMemberships(): HasMany
    {
        return $this->hasMany(ClubMembership::class)->where('status', 'pending');
    }
}


