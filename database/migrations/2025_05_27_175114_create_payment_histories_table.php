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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('parking_id'); // User ID associated with the transaction
            $table->enum('type', ['MOMO', 'CASH', 'BANK_TRANSFER', 'CARD', 'OTHER'])
                  ->default('OTHER')
                  ->index(); // Type of transaction
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->string('trx_ref', 64)->nullable(); // Unique transaction reference
            $table->string('gwRef', 64)->nullable(); // Unique transaction reference
            $table->string('channel', 64)->nullable(); // Payment channel
            $table->string('phone_number', 20)->nullable();
            $table->string('description')->nullable(); // Description of the transaction
            $table->enum('status', ['Pending', 'Completed', 'Failed', 'Refunded', 'Cancelled', 'Processing'])
                  ->default('Pending')
                  ->index(); // Transaction status
            $table->timestamps();

            $table->foreign('bank_id')
                  ->references('id')
                  ->on('banks')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
