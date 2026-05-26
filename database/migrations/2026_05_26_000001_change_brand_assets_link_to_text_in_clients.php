<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // VARCHAR(255) is too small for multiple Dropbox/Drive URLs + descriptive text.
            // Change to TEXT so admins can paste several links and notes in this field.
            $table->text('brand_assets_link')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('brand_assets_link')->nullable()->change();
        });
    }
};
