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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Customer Info
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            // Delivery / Address
            $table->string('division');
            $table->string('district');
            $table->text('street_address');
            $table->string('postal_code')->nullable();
            $table->text('notes')->nullable();

            // Financials
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Payment & Order Status
            $table->string('payment_method'); // 'cod' or 'dgepay'
            $table->string('payment_status')->default('unpaid'); // 'unpaid', 'paid', 'failed', 'cancelled'
            $table->string('order_status')->default('pending'); // 'pending', 'confirmed', 'processing', 'completed', 'cancelled'

            // DGePay Specific Tracking
            $table->string('dgepay_unique_txn_id')->nullable()->unique();
            $table->string('dgepay_txn_number')->nullable();
            $table->string('dgepay_third_party_txn_number')->nullable();
            $table->string('dgepay_channel')->nullable(); // 'bKash', 'Nagad', 'Card', etc.
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
