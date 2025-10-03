<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'isbn' => '978-2-07-061275-8',
                'category' => 'Littérature jeunesse',
                'language' => 'Français',
                'published_year' => 1943,
                'is_available' => true,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1367545443i/157993.jpg',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0-452-28423-4',
                'category' => 'Science-Fiction',
                'language' => 'Anglais',
                'published_year' => 1949,
                'is_available' => true,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1657781256i/61439040.jpg',
            ],
            [
                'title' => 'Les Misérables',
                'author' => 'Victor Hugo',
                'isbn' => '978-2-253-09633-1',
                'category' => 'Roman historique',
                'language' => 'Français',
                'published_year' => 1862,
                'is_available' => false,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1411852091i/24280.jpg',
            ],
            [
                'title' => 'Harry Potter à l\'école des sorciers',
                'author' => 'J.K. Rowling',
                'isbn' => '978-2-07-054120-1',
                'category' => 'Fantasy',
                'language' => 'Français',
                'published_year' => 1997,
                'is_available' => true,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1598823299i/42844155.jpg',
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0-7432-7356-5',
                'category' => 'Roman',
                'language' => 'Anglais',
                'published_year' => 1925,
                'is_available' => true,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1490528560i/4671.jpg',
            ],
            [
                'title' => 'L\'Étranger',
                'author' => 'Albert Camus',
                'isbn' => '978-2-07-036002-4',
                'category' => 'Roman philosophique',
                'language' => 'Français',
                'published_year' => 1942,
                'is_available' => true,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1590930002i/49552.jpg',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}

