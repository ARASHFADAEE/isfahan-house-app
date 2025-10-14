<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->decimal('price', 10, 2);
            $table->integer('duration_days')->nullable();
            $table->boolean('requires_admin_approval')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_types');
    }
};