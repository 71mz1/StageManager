<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("projects", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->date("start_date");
            $table->date("end_date");
            $table->string("priority")->default("Normal"); // Ex: Basse, Normale, Haute
            // Clé étrangère pour l'encadrant (nullable car l'assignation peut se faire après)
            $table->foreignId("encadrant_id")->nullable()->constrained("users")->onDelete("set null");
            $table->timestamps();
        });

        // Table pivot pour lier les stagiaires aux projets (relation ManyToMany)
        Schema::create("project_user", function (Blueprint $table) {
            $table->id();
            $table->foreignId("project_id")->constrained()->onDelete("cascade");
            $table->foreignId("user_id")->constrained()->onDelete("cascade"); // Référence un stagiaire
            $table->timestamps();

            // Assurer l'unicité de la paire projet/stagiaire
            $table->unique(["project_id", "user_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("project_user");
        Schema::dropIfExists("projects");
    }
};

