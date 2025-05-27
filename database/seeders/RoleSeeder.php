<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // Optional: Define permissions if needed for MVP

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()["\Spatie\Permission\PermissionRegistrar"]->forgetCachedPermissions();

        // Create Roles
        Role::create(["name" => "Admin"]);
        Role::create(["name" => "Encadrant"]);
        Role::create(["name" => "Stagiaire"]);

        // Optional: Create Permissions (can be added later for more granularity)
        // Permission::create(["name" => "manage users"]);
        // Permission::create(["name" => "manage projects"]);
        // Permission::create(["name" => "manage tasks"]);
        // Permission::create(["name" => "evaluate stagiaires"]);

        // Optional: Assign permissions to roles
        // $adminRole = Role::findByName("Admin");
        // $adminRole->givePermissionTo(Permission::all());

        // $encadrantRole = Role::findByName("Encadrant");
        // $encadrantRole->givePermissionTo(["manage projects", "manage tasks", "evaluate stagiaires"]);

        // $stagiaireRole = Role::findByName("Stagiaire");
        // Add specific permissions for stagiaires if needed

    }
}

