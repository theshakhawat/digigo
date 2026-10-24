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
        if (! Schema::hasColumn('products', 'shipping_charge')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('shipping_charge', 10, 2)->nullable()->after('low_stock_threshold');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'shipping_charge')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('shipping_charge');
            });
        }
    }
};
