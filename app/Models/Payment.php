<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'borrower_id',
        'owner_id',
        'book_id',
        'borrow_request_id',
        'amount_total',
        'amount_per_day',
        'currency',
        'status',
        'type',
        'method',
        'paid_at',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'stripe_customer_id',
    ];

    protected $casts = [
        'amount_total' => 'decimal:2',
        'amount_per_day' => 'decimal:4',
        'paid_at' => 'datetime',
    ];

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function borrowRequest(): BelongsTo
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function getAmountTotalFormattedAttribute(): string
    {
        $symbol = $this->currency ?: config('app.currency_symbol', '$');
        return number_format((float)$this->amount_total, 2) . ' ' . $symbol;
    }

    public function getAmountPerDayFormattedAttribute(): ?string
    {
        if (is_null($this->amount_per_day)) return null;
        $symbol = $this->currency ?: config('app.currency_symbol', '$');
        return number_format((float)$this->amount_per_day, 4) . ' ' . $symbol;
    }

    public function isPurchase(): bool
    {
        return $this->type === 'purchase';
    }

    public function isBorrow(): bool
    {
        return $this->type === 'borrow' || $this->type === null;
    }
}
