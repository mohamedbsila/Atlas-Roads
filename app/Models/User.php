<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];


    // allow mass assignment for now; individual controllers should validate inputs
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    // Relationships - Wishlist and Reviews
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistVotes()
    {
        return $this->belongsToMany(Wishlist::class, 'wishlist_votes')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Livres possédés par l'utilisateur
     */
    public function ownedBooks(): HasMany
    {
        return $this->hasMany(Book::class, 'ownerId');
    }

    /**
     * Demandes d'emprunt faites par cet utilisateur
     */
    public function borrowRequests(): HasMany
    {
        return $this->hasMany(BorrowRequest::class, 'borrower_id');
    }

    /**
     * Demandes d'emprunt reçues pour les livres de cet utilisateur
     */
    public function receivedBorrowRequests(): HasMany
    {
        return $this->hasMany(BorrowRequest::class, 'owner_id');
    }

    /**
     * Room reservations made by this user
     */
    public function roomReservations(): HasMany
    {
        return $this->hasMany(RoomReservation::class);
    }
}
