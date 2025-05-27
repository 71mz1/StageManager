<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::factory()->create([
            "name" => "Admin User",
            "email" => "admin@stagemanager.test",
            "password" => Hash::make("password"), // Default password
        ]);
        $admin->assignRole("Admin");

        // Create Encadrant Users (e.g., 5 for MVP)
        $encadrants = User::factory()->count(5)->create();
        $encadrants->each(function ($user) {
            $user->assignRole("Encadrant");
        });

        // Create Stagiaire Users (e.g., 10 for MVP, mixed types)
        User::factory()->count(5)->stagiaire("diplômé")->create()->each(function ($user) {
            $user->assignRole("Stagiaire");
        });
        User::factory()->count(5)->stagiaire("en_cours_de_formation")->create()->each(function ($user) {
            $user->assignRole("Stagiaire");
        });

        $this->command->info("Users seeded successfully!");
        $this->command->info("- Admin: admin@stagemanager.test / password");
        $this->command->info("- Encadrants: 5 created (password: password)");
        $this->command->info("- Stagiaires: 10 created (5 diplômé, 5 en_cours_de_formation) (password: password)");
    }
}

