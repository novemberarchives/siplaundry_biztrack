<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // Action author (Nullable in case of system actions or deleted users)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Action (e.g., 'created', 'updated', 'deleted', 'restored')
            $table->string('event');
            
            // Specific Action (Polymorphic relation: stores 'App\Models\Item' and ID '1')
            $table->morphs('auditable');
            
            // Data Snapshots (Stored as JSON for detailed diffs)
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            
            // Security Context
            $table->string('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};