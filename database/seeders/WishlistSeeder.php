<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use App\Models\User;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $wishlists = [
            [
                'user_id' => $user->id,
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'description' => 'I need this book to improve my coding skills and learn best practices',
                'priority' => 'HIGH',
                'status' => 'SEARCHING',
                'admin_notes' => 'Found a supplier, waiting for stock availability',
                'estimated_price' => 45.99,
                'estimated_days' => 7,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Gang of Four',
                'isbn' => '978-0201633610',
                'description' => 'Essential reading for software architecture',
                'priority' => 'MEDIUM',
                'status' => 'PENDING',
                'max_price' => 60.00,
            ],
            [
                'user_id' => $user->id,
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'isbn' => '978-0135957059',
                'description' => 'Want to read the updated 20th anniversary edition',
                'priority' => 'MEDIUM',
                'status' => 'FOUND',
                'admin_notes' => 'Book is available! We can order it for you.',
                'estimated_price' => 39.99,
                'estimated_days' => 3,
                'is_found' => true,
                'found_at' => now()->subDays(2),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Eloquent JavaScript',
                'author' => 'Marijn Haverbeke',
                'description' => 'Need to brush up on JavaScript fundamentals',
                'priority' => 'LOW',
                'status' => 'ORDERED',
                'admin_notes' => 'Book has been ordered from the supplier, will arrive next week',
                'estimated_price' => 32.50,
                'estimated_days' => 10,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Rare Ancient Programming Book',
                'author' => 'Unknown Author',
                'description' => 'Looking for a very rare book from the 1970s',
                'priority' => 'LOW',
                'status' => 'REJECTED',
                'admin_notes' => 'Unfortunately, this book is out of print and unavailable through our suppliers',
                'max_price' => 100.00,
            ],
            [
                'user_id' => $user->id,
                'title' => 'الخوارزميات وهياكل البيانات',
                'author' => 'محمد أحمد',
                'description' => 'كتاب عربي عن البرمجة والخوارزميات',
                'priority' => 'MEDIUM',
                'status' => 'PENDING',
                'max_price' => 35.00,
            ],
        ];

        foreach ($wishlists as $wishlist) {
            Wishlist::create($wishlist);
        }

        $this->command->info('Wishlist seeder completed successfully!');
    }
} 