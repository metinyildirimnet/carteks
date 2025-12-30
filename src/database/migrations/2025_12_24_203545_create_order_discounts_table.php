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
        Schema::create('order_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('type'); // e.g., 'bank_transfer_discount', 'coupon_discount'
            $table->string('description'); // e.g., '%5 Havale Ödeme İndirimi'
            $table->decimal('amount', 8, 2); // The actual monetary value of the discount
            $table->decimal('percentage', 5, 2)->nullable(); // e.g., 5.00 for 5%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_discounts');
    }
};
