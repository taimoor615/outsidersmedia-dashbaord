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
        Schema::table('client_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('added_by')->nullable()->after('client_id');
            $table->string('author_name')->nullable()->after('added_by');
        });
    }

    public function down(): void
    {
        Schema::table('client_notes', function (Blueprint $table) {
            $table->dropColumn(['added_by', 'author_name']);
        });
    }
};
