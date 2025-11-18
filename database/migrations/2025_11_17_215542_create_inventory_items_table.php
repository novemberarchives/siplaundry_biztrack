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
        Schema::create('inventory_items', function (Blueprint $table) {
            // ItemID (PK)
            $table->id('ItemID');
            
            // Name (e.g., "Detergent Powder", "Fabric Softener")
            $table->string('Name')->unique();

            // Category (e.g., "Chemicals", "Packaging")
            $table->string('Category')->nullable();

            // UnitPrice (Price per unit, e.g., price per kg or bottle)
            $table->decimal('UnitPrice', 10, 2);

            // Quantity (Current stock level, e.g., 50.5 kg)
            $table->decimal('Quantity', 10, 2)->default(0);

            // ReorderLevel (Trigger for reorder notice)
            $table->decimal('ReorderLevel', 10, 2)->default(10);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};