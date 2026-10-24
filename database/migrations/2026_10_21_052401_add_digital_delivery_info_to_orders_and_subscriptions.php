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
            $table->string('digital_target_email')->nullable()->after('customer_email');
            $table->string('digital_target_phone', 30)->nullable()->after('customer_phone');
            $table->text('digital_account_notes')->nullable()->after('notes');
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->string('target_email')->nullable()->after('price');
            $table->string('target_phone', 30)->nullable()->after('target_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['digital_target_email', 'digital_target_phone', 'digital_account_notes']);
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['target_email', 'target_phone']);
        });
    }
};
