<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                              // e.g. "Starter", "Professional", "Enterprise"
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);                     // Monthly price in RWF
            $table->integer('duration_days')->default(30);       // Plan duration
            $table->integer('max_zones')->nullable();             // null = unlimited
            $table->integer('max_slots')->nullable();             // null = unlimited
            $table->integer('max_users')->nullable();             // null = unlimited
            $table->boolean('momo_payments')->default(true);     // MoMo support
            $table->boolean('reports_enabled')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Add plan_id and payment fields to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->after('company_id');
            $table->string('payment_method')->nullable()->after('paid_at');  // cash, momo
            $table->string('payment_phone')->nullable()->after('payment_method');
            $table->string('trx_ref')->nullable()->after('payment_phone');

            $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'payment_method', 'payment_phone', 'trx_ref']);
        });

        Schema::dropIfExists('subscription_plans');
    }
};
