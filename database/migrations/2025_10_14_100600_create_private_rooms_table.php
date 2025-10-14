<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('private_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('room_number', 50);
            $table->enum('status', ['available', 'reserved']);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions');
            $table->timestamps();

            $table->unique(['branch_id', 'room_number'], 'private_rooms_branch_id_room_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('private_rooms');
    }
};