<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('post_type', ['standard', 'carousel', 'video'])->default('standard');

            // Content
            $table->text('caption')->nullable();
            $table->string('webpage_url')->nullable(); // For standard & carousel

            // Platform-specific content
            $table->text('facebook_message')->nullable();
            $table->text('instagram_message')->nullable();
            $table->text('linkedin_message')->nullable();
            $table->text('twitter_message')->nullable();
            $table->text('tiktok_message')->nullable();
            $table->text('youtube_message')->nullable();

            // Google Business Profile specific
            $table->enum('google_post_type', ['whats_new', 'offer', 'event'])->nullable();
            $table->string('google_title')->nullable(); // For offer/event
            $table->enum('google_button', ['none', 'book', 'order', 'buy', 'learn_more', 'sign_up'])->nullable();
            $table->string('google_button_link')->nullable();

            // Offer specific
            $table->string('offer_code')->nullable();
            $table->string('offer_link')->nullable();
            $table->text('offer_terms')->nullable();
            $table->date('offer_start_date')->nullable();
            $table->date('offer_end_date')->nullable();
            $table->time('offer_start_time')->nullable();
            $table->time('offer_end_time')->nullable();

            // Event specific
            $table->date('event_start_date')->nullable();
            $table->date('event_end_date')->nullable();
            $table->time('event_start_time')->nullable();
            $table->time('event_end_time')->nullable();

            // Scheduling
            $table->json('platforms')->nullable(); // Array of selected platforms
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('published_at')->nullable();

            // Status & Approval
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'scheduled', 'published', 'failed'])->default('draft');
            $table->enum('approval_status', ['pending', 'approved', 'changes_requested', 'rejected'])->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['client_id', 'status']);
            $table->index(['scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
