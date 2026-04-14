<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            $table->index('entry_time');
            $table->index('exit_time');
            $table->index('status');
            $table->index('plate_number');
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('trx_ref');
        });
    }

    public function down(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            $table->dropIndex(['entry_time']);
            $table->dropIndex(['exit_time']);
            $table->dropIndex(['status']);
            $table->dropIndex(['plate_number']);
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['trx_ref']);
        });
    }
};
