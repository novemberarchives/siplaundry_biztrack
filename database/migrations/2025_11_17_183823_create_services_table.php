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
        Schema::create('services', function (Blueprint $table) {
            // ServiceID (PK)
            $table->id('ServiceID');

            // Name (e.g., "Wash & Fold", "Dry Cleaning")
            $table->string('Name')->unique();

            // Description (Optional)
            $table->text('Description')->nullable();

            // BasePrice (e.g., price per kg, price per item)
            $table->decimal('BasePrice', 8, 2);

            // Unit (e.g., "kg", "item", "load")
            $table->string('Unit', 50);
            
            // We'll add timestamps here for tracking when services are added or updated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};