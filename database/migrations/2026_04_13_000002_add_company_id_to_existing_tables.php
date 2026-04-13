<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add company_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index('company_id');
        });

        // Add company_id to zones
        Schema::table('zones', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index('company_id');
        });

        // Add company_id to parkings
        Schema::table('parkings', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index('company_id');
        });

        // Add company_id to vehicles
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index('company_id');
        });

        // Add company_id to payment_histories
        Schema::table('payment_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index('company_id');
        });

        // Add slot_id to parkings for auto-assignment
        Schema::table('parkings', function (Blueprint $table) {
            $table->unsignedBigInteger('slot_id')->nullable()->after('zone_id');
            $table->foreign('slot_id')->references('id')->on('slots')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            $table->dropForeign(['slot_id']);
            $table->dropColumn('slot_id');
        });

        $tables = ['payment_histories', 'vehicles', 'parkings', 'zones', 'users'];
        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropForeign(['company_id']);
                $table->dropIndex(['company_id']);
                $table->dropColumn('company_id');
            });
        }
    }
};
