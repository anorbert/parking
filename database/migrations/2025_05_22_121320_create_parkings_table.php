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
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();
            $table->decimal('bill', 8, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // User who performed the action
           
            $table->softDeletes(); // For soft delete functionality
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users') // Ensure this matches the table name
                ->onDelete('restrict') // Restrict deletion of related companies
                ->onUpdate('cascade'); // Update balances when the company ID changes

            $table->foreign('zone_id')
                ->references('id')
                ->on('zones') // Ensure this matches the table name
                ->onDelete('restrict') // Restrict deletion of related companies
                ->onUpdate('cascade'); // Update balances when the company ID changes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkings');
    }
};
