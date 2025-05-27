<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Encadrants and Stagiaires
        $encadrants = User::role("Encadrant")->get();
        $stagiaires = User::role("Stagiaire")->get();

        if ($encadrants->isEmpty() || $stagiaires->isEmpty()) {
            $this->command->warn("Cannot seed projects: No Encadrants or Stagiaires found. Please run UserSeeder first.");
            return;
        }

        // Create Projects (e.g., 10 for MVP)
        Project::factory()->count(10)->create()->each(function ($project) use ($encadrants, $stagiaires) {
            // Assign a random encadrant
            $project->encadrant_id = $encadrants->random()->id;
            $project->save();

            // Assign 1 to 5 stagiaires randomly to each project
            $selectedStagiaires = $stagiaires->random(rand(1, 5));
            
            foreach ($selectedStagiaires as $stagiaire) {
                $project->previousStagiaires()->attach($stagiaire->id, [
                    'started_at' => now(),
                    'completed_at' => null
                ]);
            }
        });

        $this->command->info("Projects seeded successfully! (10 projects created with random supervisors and interns assigned)");
    }
}