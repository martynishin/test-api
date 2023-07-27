<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
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
        return [
            'event_title' => $this->faker->word(),
            'event_start_date' => $this->faker->dateTimeBetween('-12 hours', '-6 hours'),
            'event_end_date' => $this->faker->dateTimeBetween('-6 hours'),
        ];
    }
}
