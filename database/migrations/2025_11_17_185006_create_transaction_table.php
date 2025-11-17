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
        Schema::create('transactions', function (Blueprint $table) {
            // TransactionID (PK)
            $table->id('TransactionID');

            // CustomerID (FK from customers)
            $table->foreignId('CustomerID')->constrained('customers', 'CustomerID');

            // UserID (FK from users - 'id' is the PK of your 'users' table)
            // We name the column 'UserID' as per your ERD, but it references 'id'.
            $table->foreignId('UserID')->constrained('users', 'id');

            // DateCreated (DATE)
            $table->date('DateCreated');

            // DatePaid (DATE, Nullable)
            $table->date('DatePaid')->nullable();

            // TotalAmount (DECIMAL) - Switched from FLOAT to DECIMAL for financial accuracy
            $table->decimal('TotalAmount', 10, 2);

            // PaymentStatus (VARCHAR)
            $table->string('PaymentStatus', 100);

            // Notes (VARCHAR, Nullable)
            $table->string('Notes')->nullable();

            // NO TIMESTAMPS, as per your ERD
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};