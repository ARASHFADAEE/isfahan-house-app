<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flexible_desk_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('branch_id')->constrained('branches');
            $table->timestamp('reservation_date');
            $table->enum('status', ['confirmed', 'cancelled']);
            $table->timestamps();

            $table->index(['reservation_date'], 'flexible_desk_reservations_reservation_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flexible_desk_reservations');
    }
};