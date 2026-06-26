<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('fulfillment_status')->nullable()->after('status');
            $table->string('supplier_tracking_number')->nullable()->after('fulfillment_status');
            $table->timestamp('fulfillment_status_updated_at')->nullable()->after('supplier_tracking_number');
            $table->json('fulfillment_timeline')->nullable()->after('fulfillment_status_updated_at');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'fulfillment_status',
                'supplier_tracking_number',
                'fulfillment_status_updated_at',
                'fulfillment_timeline',
            ]);
        });
    }
};