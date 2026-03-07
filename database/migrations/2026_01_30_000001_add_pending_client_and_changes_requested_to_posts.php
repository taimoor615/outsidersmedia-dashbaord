<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE posts MODIFY COLUMN status ENUM(
            'draft',
            'pending_client',
            'changes_requested',
            'pending_approval',
            'approved',
            'rejected',
            'scheduled',
            'published',
            'failed'
        ) DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Revert to original enum (optional - may lose data for new statuses)
        DB::statement("ALTER TABLE posts MODIFY COLUMN status ENUM(
            'draft',
            'pending_approval',
            'approved',
            'rejected',
            'scheduled',
            'published',
            'failed'
        ) DEFAULT 'draft'");
    }
};
