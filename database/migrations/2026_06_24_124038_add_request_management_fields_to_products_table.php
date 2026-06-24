<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE products MODIFY status ENUM('pending_review', 'priced', 'completed', 'rejected') DEFAULT 'pending_review'");
        }

        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('quote_expires_at')->nullable()->after('status');
            $table->text('rejection_reason')->nullable()->after('quote_expires_at');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            $table->decimal('service_fee_dzd', 10, 2)->nullable()->after('final_price_dzd');
        });
    }

    public function down(): void
    {
        DB::table('products')
            ->where('status', 'rejected')
            ->update([
                'status' => 'pending_review',
            ]);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'quote_expires_at',
                'rejection_reason',
                'rejected_at',
                'service_fee_dzd',
            ]);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE products MODIFY status ENUM('pending_review', 'priced', 'completed') DEFAULT 'pending_review'");
        }
    }
};