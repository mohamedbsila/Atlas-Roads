<?php

namespace Database\Seeders;

use App\Models\BorrowRequest;
use App\Models\User;
use App\Models\Book;
use App\Enums\RequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BorrowRequestSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // S'assurer qu'il y a des utilisateurs et des livres
        $users = User::all();
        $books = Book::all();

        if ($users->count() < 2 || $books->count() < 1) {
            $this->command->info('Pas assez d\'utilisateurs ou de livres pour créer des demandes d\'emprunt.');
            return;
        }

        // Créer quelques demandes d'emprunt de test
        $borrowRequests = [
            [
                'borrower_id' => $users->random()->id,
                'owner_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(15),
                'status' => RequestStatus::PENDING,
                'notes' => 'J\'aimerais emprunter ce livre pour mes études.'
            ],
            [
                'borrower_id' => $users->random()->id,
                'owner_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(10),
                'status' => RequestStatus::APPROVED,
                'notes' => 'Merci d\'avoir accepté ma demande !'
            ],
            [
                'borrower_id' => $users->random()->id,
                'owner_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'start_date' => Carbon::now()->subDays(20),
                'end_date' => Carbon::now()->subDays(6),
                'status' => RequestStatus::RETURNED,
                'notes' => 'Livre retourné en parfait état.'
            ],
            [
                'borrower_id' => $users->random()->id,
                'owner_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'start_date' => Carbon::now()->addDays(3),
                'end_date' => Carbon::now()->addDays(17),
                'status' => RequestStatus::REJECTED,
                'notes' => 'Désolé, je ne peux pas prêter ce livre pour le moment.'
            ]
        ];

        foreach ($borrowRequests as $request) {
            BorrowRequest::create($request);
        }

        $this->command->info('Demandes d\'emprunt créées avec succès !');
    }
}
