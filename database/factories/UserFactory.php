<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "email_verified_at" => now(),
            "password" => static::$password ??= Hash::make("password"), // Default password: "password"
            "remember_token" => Str::random(10),
            "type" => null, // Default type is null (for Admin/Encadrant)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            "email_verified_at" => null,
        ]);
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Assign role based on context if needed, or handle in Seeder
            // Example: if ($user->type) { $user->assignRole("Stagiaire"); }
        });
    }

    /**
     * Indicate that the user is a Stagiaire.
     *
     * @param string $type ('diplômé' or 'en_cours_de_formation')
     * @return $this
     */
    public function stagiaire(string $type = "en_cours_de_formation")
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                "type" => $type,
            ];
        });
    }
}

