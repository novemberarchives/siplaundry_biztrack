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
        // This is a pivot table linking Services to InventoryItems
        Schema::create('inventory_usage', function (Blueprint $table) {
            // UsageID (PK)
            $table->id('UsageID');

            // ServiceID (FK from services)
            $table->foreignId('ServiceID')->constrained('services', 'ServiceID')->onDelete('cascade');

            // ItemID (FK from inventory_items)
            $table->foreignId('ItemID')->constrained('inventory_items', 'ItemID')->onDelete('cascade');

            // The amount of inventory used per 1 unit of the service
            // e.g., 0.05 (kg of detergent) per 1 (kg of Wash & Fold)
            $table->decimal('QuantityUsed', 8, 4);

            $table->timestamps();

            // Ensure a service can only be linked to an item once
            $table->unique(['ServiceID', 'ItemID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_usage');
    }
};