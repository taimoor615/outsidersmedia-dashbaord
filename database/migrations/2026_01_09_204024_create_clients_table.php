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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('website_url')->nullable();
            $table->string('location')->nullable(); // City, State/Province, Country
            $table->text('business_description')->nullable();
            $table->text('unique_value')->nullable();
            $table->text('target_audience')->nullable();
            $table->json('social_goals')->nullable(); // Array of goals
            $table->json('brand_tone')->nullable(); // Array of tones (max 3)
            $table->json('content_types')->nullable(); // Array of content priorities
            $table->text('content_to_avoid')->nullable();
            $table->string('preferred_cta')->nullable();
            $table->boolean('share_third_party_content')->default(false);
            $table->text('keywords')->nullable();
            $table->text('competitors')->nullable();
            $table->string('brand_assets_link')->nullable(); // Google Drive/Dropbox
            $table->string('timezone')->default('America/New_York');
            $table->json('posting_days')->nullable(); // Array of days
            $table->boolean('needs_approval')->default(true);
            $table->text('approval_emails')->nullable(); // Comma-separated
            $table->text('additional_notes')->nullable();
            $table->string('plan_type')->nullable(); // starter, business, scale
            $table->integer('posts_per_month')->default(8);
            $table->json('networks')->nullable(); // Array of networks (max based on plan)
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('share_token')->unique(); // For client view access
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
