<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\BorrowRequest;
use App\Enums\RequestStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des utilisateurs de test
        $user1 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@example.com', 
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Pierre Durand',
            'email' => 'pierre@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Créer des livres de test
        $book1 = Book::create([
            'title' => 'Laravel pour les débutants',
            'author' => 'John Smith',
            'isbn' => '978-0123456789',
            'category' => 'Programmation',
            'language' => 'Français',
            'published_year' => 2022,
            'is_available' => true,
            'ownerId' => $user1->id,
        ]);

        $book2 = Book::create([
            'title' => 'Vue.js Avancé',
            'author' => 'Sarah Johnson',
            'isbn' => '978-0987654321',
            'category' => 'Développement Web',
            'language' => 'Français',
            'published_year' => 2023,
            'is_available' => true,
            'ownerId' => $user2->id,
        ]);

        $book3 = Book::create([
            'title' => 'Algorithmes et Structures de Données',
            'author' => 'Robert Brown',
            'isbn' => '978-0456789123',
            'category' => 'Informatique',
            'language' => 'Français',
            'published_year' => 2021,
            'is_available' => true,
            'ownerId' => $user3->id,
        ]);

        // Créer des demandes d'emprunt de test
        BorrowRequest::create([
            'borrower_id' => $user2->id,
            'owner_id' => $user1->id,
            'book_id' => $book1->id,
            'start_date' => Carbon::now()->addDays(1),
            'end_date' => Carbon::now()->addDays(15),
            'status' => RequestStatus::PENDING,
            'notes' => 'J\'aimerais emprunter ce livre pour apprendre Laravel.',
        ]);

        BorrowRequest::create([
            'borrower_id' => $user3->id,
            'owner_id' => $user2->id,
            'book_id' => $book2->id,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(10),
            'status' => RequestStatus::APPROVED,
            'notes' => 'Merci d\'avoir accepté ma demande !',
        ]);

        BorrowRequest::create([
            'borrower_id' => $user1->id,
            'owner_id' => $user3->id,
            'book_id' => $book3->id,
            'start_date' => Carbon::now()->subDays(20),
            'end_date' => Carbon::now()->subDays(6),
            'status' => RequestStatus::RETURNED,
            'notes' => 'Livre retourné en parfait état.',
        ]);

        $this->command->info('Données de test créées avec succès !');
    }
}