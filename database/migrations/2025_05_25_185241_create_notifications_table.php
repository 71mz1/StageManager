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
        Schema::create("notifications", function (Blueprint $table) {
            $table->uuid("id")->primary(); // Use UUID for standard Laravel notifications
            $table->string("type");
            $table->morphs("notifiable");
            $table->text("data");
            $table->timestamp("read_at")->nullable();
            $table->timestamps();
        });

        // Table for simple MVP notifications (alternative or complementary)
        Schema::create("simple_notifications", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade"); // The user receiving the notification
            $table->string("message");
            $table->string("link")->nullable(); // Optional link related to the notification
            $table->boolean("is_read")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("simple_notifications");
        Schema::dropIfExists("notifications"); // Drop standard Laravel notifications table
    }
};

