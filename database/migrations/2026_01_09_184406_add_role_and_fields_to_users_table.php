<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'team','client'])->default('client')->after('email');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
            $table->string('profile_image')->nullable()->after('status');
            $table->string('timezone')->default('America/New_York')->after('profile_image');
            $table->timestamp('last_login_at')->nullable()->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'profile_image', 'timezone', 'last_login_at']);
        });
    }
};
