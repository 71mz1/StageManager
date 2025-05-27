<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Projects and Stagiaires
        $projects = Project::all();
        $stagiaires = User::role("Stagiaire")->get();

        if ($projects->isEmpty() || $stagiaires->isEmpty()) {
            $this->command->warn("Cannot seed tasks: No Projects or Stagiaires found. Please run ProjectSeeder and UserSeeder first.");
            return;
        }

        // Create Tasks for each project (e.g., 5 tasks per project for MVP)
        foreach ($projects as $project) {
            // Get stagiaires assigned to this specific project
            $projectStagiaires = $project->previousStagiaires;
            if ($projectStagiaires->isEmpty()) {
                continue; // Skip if no interns assigned to this project
            }

            Task::factory()->count(5)->create([
                "project_id" => $project->id,
                // Assign a random stagiaire from those assigned to the project
                "assignee_id" => $projectStagiaires->random()->id, 
            ]);
        }

        $this->command->info("Tasks seeded successfully! (5 tasks per project created with random assignees from project interns)");
    }
}
