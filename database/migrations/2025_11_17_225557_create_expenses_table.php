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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id('ExpenseID');
            
            // Link to the inventory item that was purchased
            $table->foreignId('ItemID')->constrained('inventory_items', 'ItemID');

            $table->date('Date');
            $table->decimal('QuantityPurchased', 10, 2);
            $table->decimal('TotalCost', 10, 2);
            $table->string('Remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};