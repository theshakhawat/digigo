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
            $table->decimal('delivery_charge_inside_dhaka', 10, 2)->nullable()->after('is_featured');
            $table->decimal('delivery_charge_outside_dhaka', 10, 2)->nullable()->after('delivery_charge_inside_dhaka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_charge_inside_dhaka',
                'delivery_charge_outside_dhaka',
            ]);
        });
    }
};
