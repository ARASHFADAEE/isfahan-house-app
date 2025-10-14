<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('subscription_type_id')->constrained('subscription_types');
            $table->foreignId('branch_id')->constrained('branches');
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime');
            $table->enum('status', ['pending', 'active', 'expired']);
            $table->decimal('total_price', 10, 2);
            $table->foreignId('discount_id')->nullable()->constrained('discounts');
            $table->timestamps();

            $table->index(['user_id', 'branch_id'], 'subscriptions_user_id_branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};