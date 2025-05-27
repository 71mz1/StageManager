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
        Schema::create("tasks", function (Blueprint $table) {
            $table->id();
            $table->foreignId("project_id")->constrained()->onDelete("cascade");
            $table->string("title");
            $table->text("description");
            $table->string("duration")->nullable(); // Durée estimée (ex: "2 jours", "8 heures")
            $table->string("priority")->default("Normal"); // Ex: Basse, Normale, Haute
            $table->string("status")->default("A faire"); // Ex: A faire, En cours, Terminé, Bloqué
            // Clé étrangère pour le stagiaire assigné (nullable si non assigné ou assigné plus tard)
            $table->foreignId("assignee_id")->nullable()->constrained("users")->onDelete("set null"); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tasks");
    }
};

