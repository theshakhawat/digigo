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
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('affiliate_commission_physical', 8, 2)->nullable()->after('delivery_charge_outside_dhaka');
            $table->string('affiliate_commission_type_physical', 20)->default('percentage')->after('affiliate_commission_physical'); // percentage or fixed
            $table->decimal('affiliate_commission_digital', 8, 2)->nullable()->after('affiliate_commission_type_physical');
            $table->string('affiliate_commission_type_digital', 20)->default('percentage')->after('affiliate_commission_digital'); // percentage or fixed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'affiliate_commission_physical',
                'affiliate_commission_type_physical',
                'affiliate_commission_digital',
                'affiliate_commission_type_digital',
            ]);
        });
    }
};
