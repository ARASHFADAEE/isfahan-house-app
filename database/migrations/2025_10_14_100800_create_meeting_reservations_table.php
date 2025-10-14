<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meeting_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('meeting_room_id')->constrained('meeting_rooms');
            $table->timestamp('reservation_date');
            $table->integer('duration_hours');
            $table->enum('status', ['pending', 'confirmed', 'cancelled']);
            $table->timestamps();

            $table->index(['reservation_date'], 'meeting_reservations_reservation_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_reservations');
    }
};