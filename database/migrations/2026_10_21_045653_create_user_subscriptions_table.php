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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('product_plans')->nullOnDelete();

            $table->string('product_name');
            $table->string('plan_name')->nullable();
            $table->string('validity')->nullable(); // e.g. '1 Month', '1 Year'
            $table->decimal('price', 10, 2)->default(0.00);

            // Credentials / Delivery Info supplied by Admin upon activation
            $table->text('credentials')->nullable(); // username, password, email, profile pin, etc.
            $table->text('access_instructions')->nullable();
            $table->text('license_key')->nullable();
            $table->string('login_url')->nullable();

            // Status: pending (initial), active (approved/provided by admin), expired, cancelled
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('activated_at')->nullable();

            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
