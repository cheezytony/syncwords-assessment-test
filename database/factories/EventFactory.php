<?php

namespace Database\Factories;

use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $endDate = now();
        $startDate = now()->subHours(6);
        return [
            'event_title' => $this->faker->sentence(3),
            'event_start_date' => $startDate,
            'event_end_date' => $endDate,
        ];
    }
}
