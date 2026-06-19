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
        Schema::table('orders', function (Blueprint $table) {
            // 1. Fix the integer rate_used column to handle proper currency decimals
            $table->decimal('rate_used', 8, 2)->nullable()->change();

            // 2. Add the crucial missing financial snapshot data
            $table->decimal('price_usd', 10, 2)->after('product_id');
            $table->decimal('total_dzd', 10, 2)->after('price_usd');

            // 3. Add item snapshots so history stays intact if a product is altered/deleted
            $table->string('product_title')->after('product_id');
            $table->string('selected_size')->nullable()->after('product_title');
            $table->string('selected_color')->nullable()->after('selected_size');
            $table->string('custom_note')->nullable()->after('selected_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //$table->integer('rate_used')->change();
            
            $table->dropColumn([
                'price_usd', 
                'total_dzd', 
                'product_title', 
                'selected_size', 
                'selected_color',
                'custom_note'
            ]);
        });
    }
};
