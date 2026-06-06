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
        Schema::create('chargily_payments', function (Blueprint $table) {
            $table->id();
            
            // Sets up user relation cleanly from the start
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Sets up order relation cleanly from the start
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            
            $table->enum("status", ["pending", "paid", "failed"])->default("pending");
            $table->string("currency");
            $table->string("amount");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chargily_payments');
    }
};
