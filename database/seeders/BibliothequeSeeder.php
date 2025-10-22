<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bibliotheque;

class BibliothequeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bibliotheques = [
            [
                'name' => 'Bibliothèque Centrale de Paris',
                'description' => 'La bibliothèque centrale offre une vaste collection de livres dans tous les domaines, avec des espaces de lecture modernes et confortables.',
                'address' => '123 Avenue des Champs-Élysées',
                'city' => 'Paris',
                'postal_code' => '75008',
                'phone' => '+33 1 42 86 82 00',
                'email' => 'contact@biblio-paris.fr',
                'website' => 'https://www.bibliotheque-paris.fr',
                'opening_time' => '09:00',
                'closing_time' => '19:00',
                'opening_days' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                'capacity' => 50000,
                'is_active' => true,
                'latitude' => 48.8566,
                'longitude' => 2.3522,
            ],
            [
                'name' => 'Médiathèque Municipale de Lyon',
                'description' => 'Une médiathèque moderne proposant livres, magazines, films et espaces numériques pour tous les âges.',
                'address' => '30 Rue de la République',
                'city' => 'Lyon',
                'postal_code' => '69002',
                'phone' => '+33 4 72 10 30 30',
                'email' => 'mediatheque@lyon.fr',
                'website' => 'https://www.mediatheque-lyon.fr',
                'opening_time' => '10:00',
                'closing_time' => '18:00',
                'opening_days' => ['Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                'capacity' => 30000,
                'is_active' => true,
                'latitude' => 45.7640,
                'longitude' => 4.8357,
            ],
            [
                'name' => 'Bibliothèque Universitaire de Marseille',
                'description' => 'Bibliothèque spécialisée dans les ressources académiques avec accès numérique 24/7.',
                'address' => '58 Boulevard Charles Livon',
                'city' => 'Marseille',
                'postal_code' => '13007',
                'phone' => '+33 4 91 39 65 00',
                'email' => 'bu@univ-marseille.fr',
                'opening_time' => '08:00',
                'closing_time' => '20:00',
                'opening_days' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
                'capacity' => 75000,
                'is_active' => true,
                'latitude' => 43.2965,
                'longitude' => 5.3698,
            ],
        ];

        foreach ($bibliotheques as $bibliotheque) {
            Bibliotheque::create($bibliotheque);
        }

        $this->command->info('✅ Created ' . count($bibliotheques) . ' bibliotheques');
    }
}
