<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('message', 500);
            $table->enum('type', ['sms', 'email', 'system']);
            $table->enum('event_type', ['subscription_created', 'subscription_approved', 'subscription_expiring', 'subscription_expired']);
            $table->enum('status', ['sent', 'pending', 'failed']);
            $table->string('external_id', 100)->nullable();
            $table->timestamps();

            $table->index(['user_id'], 'notifications_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};