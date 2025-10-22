<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Book;
use App\Models\Event;
use App\Models\Review;
use App\Models\Reclamation;
use App\Models\Wishlist;
use App\Models\BorrowRequest;
use App\Enums\RequestStatus;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@softui.com',
            'password' => Hash::make('secret'),
            'is_admin' => true,
            'about' => "Hi, I'm the admin. I manage the library and help users find books.",
            'phone' => '+1234567890',
            'location' => 'Paris, France',
        ]);

        // Create regular users
        $users = [
            User::create([
                'name' => 'Jean Dupont',
                'email' => 'jean@example.com',
                'password' => Hash::make('password'),
                'phone' => '+33612345678',
                'location' => 'Lyon, France',
                'about' => 'Passionate about classic literature and programming books.',
            ]),
            User::create([
                'name' => 'Marie Martin',
                'email' => 'marie@example.com',
                'password' => Hash::make('password'),
                'phone' => '+33698765432',
                'location' => 'Marseille, France',
                'about' => 'Love reading sci-fi and fantasy novels.',
            ]),
            User::create([
                'name' => 'Pierre Durand',
                'email' => 'pierre@example.com',
                'password' => Hash::make('password'),
                'phone' => '+33687654321',
                'location' => 'Toulouse, France',
                'about' => 'Enjoy technical books and history.',
            ]),
        ];

        // Create books
        $books = [
            Book::create([
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'isbn' => '978-2-07-061275-8',
                'category' => 'Littérature jeunesse',
                'language' => 'Français',
                'published_year' => 1943,
                'is_available' => true,
                'ownerId' => $users[0]->id,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1367545443i/157993.jpg',
            ]),
            Book::create([
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0-452-28423-4',
                'category' => 'Science-Fiction',
                'language' => 'Anglais',
                'published_year' => 1949,
                'is_available' => true,
                'ownerId' => $users[1]->id,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1657781256i/61439040.jpg',
            ]),
            Book::create([
                'title' => 'Les Misérables',
                'author' => 'Victor Hugo',
                'isbn' => '978-2-253-09633-1',
                'category' => 'Roman historique',
                'language' => 'Français',
                'published_year' => 1862,
                'is_available' => false,
                'ownerId' => $users[0]->id,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1411852091i/24280.jpg',
            ]),
            Book::create([
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'category' => 'Programmation',
                'language' => 'Anglais',
                'published_year' => 2008,
                'is_available' => true,
                'ownerId' => $users[2]->id,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1436202607i/3735293.jpg',
            ]),
            Book::create([
                'title' => 'Harry Potter à l\'école des sorciers',
                'author' => 'J.K. Rowling',
                'isbn' => '978-2-07-054120-1',
                'category' => 'Fantasy',
                'language' => 'Français',
                'published_year' => 1997,
                'is_available' => true,
                'ownerId' => $users[1]->id,
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1598823299i/42844155.jpg',
            ]),
        ];

        // Create borrow requests
        BorrowRequest::create([
            'borrower_id' => $users[1]->id,
            'owner_id' => $users[0]->id,
            'book_id' => $books[0]->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(15),
            'status' => 'pending',
            'notes' => 'I\'d love to read this classic!',
        ]);

        BorrowRequest::create([
            'borrower_id' => $users[2]->id,
            'owner_id' => $users[1]->id,
            'book_id' => $books[1]->id,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(10),
            'status' => 'approved',
            'notes' => 'Thanks for lending this book!',
        ]);

        // Create reviews
        Review::create([
            'book_id' => $books[0]->id,
            'user_id' => $users[1]->id,
            'rating' => 5,
            'comment' => 'A timeless masterpiece! The story is touching and profound.',
        ]);

        Review::create([
            'book_id' => $books[1]->id,
            'user_id' => $users[0]->id,
            'rating' => 4,
            'comment' => 'Very thought-provoking. A must-read for understanding dystopian literature.',
        ]);

        // Create wishlists
        Wishlist::create([
            'user_id' => $users[0]->id,
            'title' => 'The Lord of the Rings',
            'author' => 'J.R.R. Tolkien',
            'isbn' => '978-0544003415',
            'description' => 'Looking for the complete trilogy',
            'priority' => 'high',
            'status' => 'pending',
            'max_price' => 50.00,
        ]);

        Wishlist::create([
            'user_id' => $users[2]->id,
            'title' => 'Design Patterns',
            'author' => 'Gang of Four',
            'isbn' => '978-0201633610',
            'description' => 'Essential for software architecture',
            'priority' => 'medium',
            'status' => 'found',
            'is_found' => true,
            'found_at' => now(),
            'estimated_price' => 45.00,
        ]);

        // Create reclamations
        Reclamation::create([
            'user_id' => $users[1]->id,
            'titre' => 'Livre endommagé',
            'description' => 'Le livre que j\'ai reçu a des pages déchirées.',
            'categorie' => 'Qualité',
            'priorite' => 'haute',
            'statut' => 'en_attente',
        ]);

        Reclamation::create([
            'user_id' => $users[0]->id,
            'titre' => 'Délai de livraison',
            'description' => 'Le livre n\'est toujours pas arrivé après 2 semaines.',
            'categorie' => 'Livraison',
            'priorite' => 'moyenne',
            'statut' => 'en_cours',
        ]);

        $this->command->info('✅ Database populated successfully!');
        $this->command->info('📚 Created: 4 users, 5 books, 2 borrow requests, 2 reviews, 2 wishlists, 2 reclamations');
        $this->command->info('🔐 Admin login: admin@softui.com / secret');
        $this->command->info('👤 User login: jean@example.com / password');
    }
}
