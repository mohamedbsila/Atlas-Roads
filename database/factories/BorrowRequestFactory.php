<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Book;
use App\Enums\RequestStatus;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BorrowRequest>
 */
class BorrowRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-30 days', '+30 days');
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 30) . ' days');

        return [
            'borrower_id' => User::factory(),
            'owner_id' => User::factory(),
            'book_id' => Book::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement([
                RequestStatus::PENDING,
                RequestStatus::APPROVED,
                RequestStatus::REJECTED,
                RequestStatus::RETURNED,
            ]),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Demande en attente
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::PENDING,
            'start_date' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'end_date' => fake()->dateTimeBetween('+2 days', '+60 days'),
        ]);
    }

    /**
     * Demande approuvée
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::APPROVED,
            'start_date' => fake()->dateTimeBetween('-10 days', 'now'),
            'end_date' => fake()->dateTimeBetween('+1 day', '+20 days'),
        ]);
    }

    /**
     * Demande retournée
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::RETURNED,
            'start_date' => fake()->dateTimeBetween('-60 days', '-20 days'),
            'end_date' => fake()->dateTimeBetween('-19 days', '-1 day'),
        ]);
    }

    /**
     * Demande rejetée
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::REJECTED,
        ]);
    }
}
