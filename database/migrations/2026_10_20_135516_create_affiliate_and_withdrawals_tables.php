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
        // Add affiliate tracking columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('affiliate_code', 32)->nullable()->unique()->after('status');
            $table->decimal('affiliate_balance', 10, 2)->default(0)->after('affiliate_code');
            $table->decimal('total_affiliate_earned', 10, 2)->default(0)->after('affiliate_balance');
            $table->decimal('total_affiliate_withdrawn', 10, 2)->default(0)->after('total_affiliate_earned');
        });

        // Add affiliate tracking columns to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('affiliate_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->decimal('affiliate_commission', 10, 2)->default(0)->after('total');
            $table->boolean('affiliate_commission_paid')->default(false)->after('affiliate_commission');
        });

        // Create affiliate earnings ledger table
        Schema::create('affiliate_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // 'pending', 'credited', 'cancelled'
            $table->timestamp('credited_at')->nullable();
            $table->timestamps();
        });

        // Create withdrawal requests table
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 32)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('method'); // 'mfs', 'bank', 'dgepay'
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'

            // Payment destination details stored as JSON
            $table->json('payment_details');

            // Processing & admin notes
            $table->text('admin_note')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('affiliate_earnings');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['affiliate_user_id']);
            $table->dropColumn(['affiliate_user_id', 'affiliate_commission', 'affiliate_commission_paid']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['affiliate_code', 'affiliate_balance', 'total_affiliate_earned', 'total_affiliate_withdrawn']);
        });
    }
};
