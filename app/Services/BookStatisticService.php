<?php

namespace App\Services;

use App\Models\Book;

class BookStatisticService
{
    

    public function getStatistics(Book $book)
    {
        return [
            'views' => $book->views ?? 0,
            'likes' => $book->likes ?? 0,
            // ajoute d'autres stats ici
        ];
    }
    public static function updateBookStats(Book $book, array $data)
{
    $book->update($data);
}

}
