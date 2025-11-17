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
        Schema::create('transaction_details', function (Blueprint $table) {
            // TransactionDetailID (PK)
            $table->id('TransactionDetailID');

            // TransactionID (FK from transactions)
            $table->foreignId('TransactionID')->constrained('transactions', 'TransactionID');
            
            // ServiceID (FK from services - this is your "JobType")
            $table->foreignId('ServiceID')->constrained('services', 'ServiceID');

            // Quantity (e.g., 5 shirts)
            $table->integer('Quantity')->nullable();

            // Weight (e.g., 2.5 kg)
            $table->decimal('Weight', 8, 2)->nullable();

            // PricePerUnit (A snapshot of the price at the time of sale)
            $table->decimal('PricePerUnit', 8, 2);
            
            // Subtotal (Calculated: (Quantity or Weight) * PricePerUnit)
            $table->decimal('Subtotal', 10, 2);

            // Status (Tracks this specific item)
            $table->string('Status', 50)->default('Pending'); // e.g., "Pending", "Washing", "Ready"

            // StartDate (When this job started)
            $table->date('StartDate')->nullable();

            // EndDate (When this job finished)
            $table->date('EndDate')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};