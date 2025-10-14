<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meeting_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('room_number', 50);
            $table->decimal('price_per_hour', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'room_number'], 'meeting_rooms_branch_id_room_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_rooms');
    }
};