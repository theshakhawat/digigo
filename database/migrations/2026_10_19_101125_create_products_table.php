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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('product_type')->default('physical')->index(); // 'physical', 'digital'

            // Categorization
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();

            // Media & Presentation
            $table->string('thumbnail')->nullable();
            $table->json('gallery')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('features')->nullable();

            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();

            // Status & Visibility
            $table->string('status')->default('active')->index(); // 'active', 'inactive', 'draft'
            $table->boolean('is_featured')->default(false)->index();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->unsignedInteger('reviews_count')->default(0);

            // Physical Product Specific Fields
            $table->string('sku')->nullable()->index();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('color')->nullable();
            $table->string('warranty_period')->nullable();
            $table->string('package_contents')->nullable();

            // Digital Product Specific Fields
            $table->string('digital_delivery_type')->nullable(); // 'credentials', 'license_key', 'direct_activation', 'file_download'
            $table->text('access_instructions')->nullable();
            $table->string('download_link')->nullable();
            $table->string('download_file')->nullable();
            $table->string('default_validity')->nullable(); // '1 Month', '6 Months', '1 Year', 'Lifetime'
            $table->boolean('has_plans')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
