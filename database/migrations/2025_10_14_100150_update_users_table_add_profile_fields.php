<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 255)->after('name');
            $table->string('last_name', 255)->after('first_name');
            $table->enum('role', ['user', 'admin', 'ceo'])->default('user')->after('password');
            $table->string('phone', 20)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'role', 'phone']);
        });
    }
};