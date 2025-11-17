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
        Schema::create('customer', function (Blueprint $table) {
              // CustomerID (PK)
            $table->id('CustomerID'); 

            // Name (Non-nullable)
            $table->string('Name'); 

            // ContactNumber (Must be unique for quick lookup)
            $table->string('ContactNumber')->unique(); 

            // Address (New, Nullable)
            $table->string('Address')->nullable();
            
            // Email (New, Nullable, and unique if provided)
            $table->string('Email')->nullable()->unique();
            
            // DateCreated (New, using DATE type as requested, set manually in controller)
            $table->date('DateCreated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
