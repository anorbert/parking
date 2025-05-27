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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->default(3);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('otp', 6)->nullable();
            $table->string('phone_number')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes

            $table->foreign('role_id')
                ->references('id')
                ->on('roles') // Ensure this matches the table name
                ->onDelete('restrict') // Restrict deletion of related companies
                ->onUpdate('cascade'); // Update balances when the company ID changes
                
            $table->foreign('zone_id')
                ->references('id')
                ->on('zones') // Ensure this matches the table name
                ->onDelete('set null') // Set zone_id to null if the zone is deleted
                ->onUpdate('cascade'); // Update zone_id when the zone ID changes
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
