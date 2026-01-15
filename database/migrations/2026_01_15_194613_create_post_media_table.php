<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->enum('type', ['image', 'video'])->default('image');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size')->nullable(); // in KB
            $table->string('mime_type')->nullable();
            $table->integer('order')->default(0); // For carousel ordering
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_media');
    }
};
