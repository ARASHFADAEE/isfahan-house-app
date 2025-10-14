<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions');
            $table->foreignId('meeting_reservation_id')->nullable()->constrained('meeting_reservations');
            $table->foreignId('branch_id')->constrained('branches');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['online', 'card', 'cash']);
            $table->string('transaction_code', 100)->unique();
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->timestamps();

            $table->index(['user_id'], 'transactions_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};