<?php

namespace App\Observers;

use App\Models\Book;

class BookObserver
{
    /**
     * Handle the Book "creating" event.
     * This runs before the book is saved to the database.
     */
    public function creating(Book $book): void
    {
        // Automatically set the owner to the current user if not set
        if (!$book->ownerId && auth()->check()) {
            $book->ownerId = auth()->id();
        }
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}
