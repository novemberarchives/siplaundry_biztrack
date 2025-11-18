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
        Schema::table('services', function (Blueprint $table) {
            // Add a nullable decimal column for minimums.
            // (e.g., 1.5 for kg, or 1.0 for item)
            // 'null' means no minimum.
            $table->decimal('MinQuantity', 8, 2)->nullable()->default(null)->after('Unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('MinQuantity');
        });
    }
};