<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdBy = User::inRandomOrder()->first();

        return [
            "name" => fake()->bs(),
            "created_by" => $createdBy->id,
            "last_message_at" => fake()->dateTimeBetween("-1 month", "now"),
        ];
    }
}
