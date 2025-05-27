<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure projects exist before creating tasks
        $project = Project::inRandomOrder()->first();
        // Ensure stagiaires exist before assigning tasks
        $assignee = User::role("Stagiaire")->inRandomOrder()->first();

        return [
            "project_id" => $project?->id ?? Project::factory(), // Create a project if none exists
            "title" => fake()->bs(), // Generate business-like short text
            "description" => fake()->paragraph(2),
            "duration" => fake()->randomElement(["1 jour", "2 jours", "4 heures", "8 heures", "3 jours"]), 
            "priority" => fake()->randomElement(["Basse", "Normale", "Haute"]),
            "status" => fake()->randomElement(["A faire", "En cours", "Terminé", "Bloqué"]),
            // Assign an existing Stagiaire user, or null
            "assignee_id" => $assignee?->id ?? null, 
        ];
    }
}

