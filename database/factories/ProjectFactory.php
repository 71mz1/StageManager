<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->sentence(3), // Generate a short sentence for the project name
            "description" => fake()->paragraph(),
            "start_date" => fake()->dateTimeBetween("-1 month", "+1 month"),
            "end_date" => fake()->dateTimeBetween("+2 months", "+6 months"),
            "priority" => fake()->randomElement(["Basse", "Normale", "Haute"]),
            // Assign an existing Encadrant user, or null. Ensure Encadrant users exist first.
            "encadrant_id" => User::role("Encadrant")->inRandomOrder()->first()?->id ?? null, 
        ];
    }
}

