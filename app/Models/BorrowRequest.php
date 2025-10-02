<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_id',
        'owner_id', 
        'book_id',
        'start_date',
        'end_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => RequestStatus::class,
    ];

    /**
     * Relation avec l'utilisateur emprunteur
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    /**
     * Relation avec l'utilisateur propriétaire du livre
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relation avec le livre emprunté
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Créer une nouvelle demande d'emprunt
     */
    public static function createRequest(int $borrowerId, int $bookId, Carbon $startDate, Carbon $endDate, ?string $notes = null): self
    {
        $book = Book::findOrFail($bookId);
        
        return self::create([
            'borrower_id' => $borrowerId,
            'owner_id' => $book->ownerId,
            'book_id' => $bookId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => RequestStatus::PENDING,
            'notes' => $notes
        ]);
    }

    /**
     * Approuver la demande d'emprunt
     */
    public function approveRequest(): bool
    {
        if ($this->status !== RequestStatus::PENDING) {
            return false;
        }

        $this->status = RequestStatus::APPROVED;
        return $this->save();
    }

    /**
     * Rejeter la demande d'emprunt
     */
    public function rejectRequest(): bool
    {
        if ($this->status !== RequestStatus::PENDING) {
            return false;
        }

        $this->status = RequestStatus::REJECTED;
        return $this->save();
    }

    /**
     * Marquer comme retourné
     */
    public function markAsReturned(): bool
    {
        if ($this->status !== RequestStatus::APPROVED) {
            return false;
        }

        $this->status = RequestStatus::RETURNED;
        return $this->save();
    }

    /**
     * Vérifier si la demande est en cours
     */
    public function isActive(): bool
    {
        return $this->status === RequestStatus::APPROVED;
    }

    /**
     * Vérifier si la demande est en attente
     */
    public function isPending(): bool
    {
        return $this->status === RequestStatus::PENDING;
    }

    /**
     * Vérifier si la demande est terminée
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, [RequestStatus::REJECTED, RequestStatus::RETURNED]);
    }

    /**
     * Calculer la durée de l'emprunt en jours
     */
    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Vérifier si l'emprunt est en retard
     */
    public function isOverdue(): bool
    {
        return $this->status === RequestStatus::APPROVED && 
               $this->end_date->isPast();
    }
}