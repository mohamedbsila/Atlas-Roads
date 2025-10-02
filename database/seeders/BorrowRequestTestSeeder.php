<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\BorrowRequest;
use App\Enums\RequestStatus;

class BorrowRequestTestSeeder extends Seeder
{
    public function run()
    {
        // Créer des utilisateurs de test
        $owner = User::firstOrCreate(
            ['email' => 'owner@test.com'],
            [
                'name' => 'Propriétaire Test',
                'password' => bcrypt('password')
            ]
        );

        $borrower = User::firstOrCreate(
            ['email' => 'borrower@test.com'],
            [
                'name' => 'Emprunteur Test',
                'password' => bcrypt('password')
            ]
        );

        // Créer un livre pour le propriétaire
        $book = Book::firstOrCreate(
            ['title' => 'Test Livre pour Emprunt'],
            [
                'author' => 'Auteur Test',
                'category' => 'Fiction',
                'published_year' => 2023,
                'language' => 'Français',
                'ownerId' => $owner->id,
                'image' => 'https://via.placeholder.com/300x400',
                'is_available' => true
            ]
        );

        // Créer une demande d'emprunt EN ATTENTE
        $request = BorrowRequest::create([
            'borrower_id' => $borrower->id,
            'owner_id' => $owner->id,
            'book_id' => $book->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addWeek(),
            'status' => RequestStatus::PENDING
        ]);

        $this->command->info('Données de test créées:');
        $this->command->info('Propriétaire: ' . $owner->email . ' (mot de passe: password)');
        $this->command->info('Emprunteur: ' . $borrower->email . ' (mot de passe: password)');
        $this->command->info('Livre: ' . $book->title);
        $this->command->info('Demande ID: ' . $request->id . ' - Statut: ' . $request->status->value);
        $this->command->info('');
        $this->command->info('Pour tester:');
        $this->command->info('1. Connectez-vous avec owner@test.com');
        $this->command->info('2. Allez sur /borrow-requests');
        $this->command->info('3. Cliquez sur "Demandes Reçues"');
        $this->command->info('4. Vous devriez voir le bouton "Approuver"');
    }
}