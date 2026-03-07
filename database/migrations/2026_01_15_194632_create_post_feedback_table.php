<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Team member responding
            $table->string('client_name')->nullable(); // If feedback from client portal
            $table->text('feedback');
            $table->enum('action', ['approve', 'request_changes', 'reject'])->nullable();
            $table->boolean('is_client_feedback')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_feedback');
    }
};
