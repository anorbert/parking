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
        Schema::create('payment_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->text('token')->nullable();
            $table->dateTime('expired_at')->nullable();
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
        Schema::dropIfExists('payment_tokens');
    }
};
