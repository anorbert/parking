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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('vehicle_type')->nullable(); // e.g., car, motorcycle, truck
            $table->string('owner_name'); // Name of the vehicle owner
            $table->string('owner_contact')->nullable(); // Contact information of the owner
            $table->string('billing_type')->default('Free')->nullable();
            $table->string('reason')->nullable(); // e.g., Government, Staff, Emergency
            //expired at
            $table->dateTime('expired_at')->nullable(); // Expiration date for the vehicle's exemption
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
