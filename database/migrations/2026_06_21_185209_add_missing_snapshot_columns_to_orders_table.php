<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Production Stability Pass — B1 + B2
 *
 * B1: product_image was dropped in 2026_04_03 but OrderController::store()
 *     still writes it, causing a SQL error on every order creation.
 *
 * B2: quantity was always in Order::$fillable and OrderController::store()
 *     but the column was never added to the orders table.
 *
 * Both columns are added as nullable so existing rows are unaffected.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // B1: restore the product image snapshot column
            // nullable() ensures no impact on existing order rows
            $table->string('product_image')->nullable()->after('product_title');

            // B2: add the quantity snapshot column
            // default(1) matches the products table default
            // nullable() ensures no impact on existing order rows
            $table->integer('quantity')->nullable()->default(1)->after('custom_note');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['product_image', 'quantity']);
        });
    }
};