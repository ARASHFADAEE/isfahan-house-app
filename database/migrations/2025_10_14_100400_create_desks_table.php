<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('desks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('desk_number', 50);
            $table->enum('status', ['available', 'reserved']);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions');
            $table->timestamps();

            $table->unique(['branch_id', 'desk_number'], 'desks_branch_id_desk_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desks');
    }
};