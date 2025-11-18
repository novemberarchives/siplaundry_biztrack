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
        Schema::create('reorder_notices', function (Blueprint $table) {
            $table->id('NoticeID');

            // Link to the item that needs reordering
            $table->foreignId('ItemID')->constrained('inventory_items', 'ItemID');

            $table->date('NoticeDate');
            $table->enum('Status', ['Pending', 'Resolved'])->default('Pending');
            $table->date('ResolvedDate')->nullable();
            $table->string('Notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reorder_notices');
    }
};