<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RoomReservation extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate and set total price based on room rate and duration
     */
    public function calculateTotalPrice(): void
    {
        $this->total_price = $this->room->calculatePrice($this->start_time, $this->end_time);
    }

    /**
     * Get duration in hours
     */
    public function getDurationInHours(): float
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        $hours = $end->diffInHours($start);
        $minutes = $end->diffInMinutes($start) % 60;
        
        return $hours + ($minutes / 60);
    }

    /**
     * Check if reservation is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->date >= now()->toDateString() && $this->status !== 'cancelled';
    }

    /**
     * Check if reservation is active now
     */
    public function isActiveNow(): bool
    {
        if ($this->date != now()->toDateString() || $this->status !== 'confirmed') {
            return false;
        }

        $now = now()->format('H:i:s');
        return $now >= $this->start_time && $now <= $this->end_time;
    }

    /**
     * Confirm reservation
     */
    public function confirm(): bool
    {
        $this->status = 'confirmed';
        return $this->save();
    }

    /**
     * Cancel reservation
     */
    public function cancel(): bool
    {
        $this->status = 'cancelled';
        return $this->save();
    }
}
