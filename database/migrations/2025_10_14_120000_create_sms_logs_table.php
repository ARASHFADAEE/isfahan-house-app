<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->json('recipients');
            $table->text('message')->nullable();
            $table->string('template')->nullable();
            $table->json('variables')->nullable();
            $table->string('provider');
            $table->string('status'); // queued, sent, failed, disabled
            $table->string('provider_message_id')->nullable();
            $table->text('raw_response')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['provider', 'status']);
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};