<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'isbn' => fake()->isbn13(),
            'category' => fake()->randomElement([
                'Fiction',
                'Non-fiction',
                'Science',
                'Histoire',
                'Biographie',
                'Programmation',
                'Art',
                'Cuisine',
                'Voyage',
                'Philosophie'
            ]),
            'language' => fake()->randomElement(['Français', 'Anglais', 'Espagnol']),
            'price' => fake()->randomFloat(2, 5, 120),
            'published_year' => fake()->numberBetween(1900, 2024),
            'is_available' => fake()->boolean(80),
            'ownerId' => User::factory(),
        ];
    }
}
