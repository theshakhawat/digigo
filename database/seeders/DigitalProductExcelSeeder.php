<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class DigitalProductExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xlsxPath = storage_path('app/public/digital_product.xlsx');
        if (! file_exists($xlsxPath)) {
            $this->command->error("Excel file not found at: {$xlsxPath}");

            return;
        }

        $this->command->info('Opening digital_product.xlsx...');
        $zip = new ZipArchive;
        if ($zip->open($xlsxPath) !== true) {
            $this->command->error('Unable to open digital_product.xlsx via ZipArchive.');

            return;
        }

        // 1. Read Lookup sheets, Products, and Variants
        $lookups = $this->parseSheet($zip, 'xl/worksheets/sheet5.xml');
        $productsRaw = $this->parseSheet($zip, 'xl/worksheets/sheet1.xml');
        $variantsRaw = $this->parseSheet($zip, 'xl/worksheets/sheet2.xml');
        $this->command->info('Raw products parsed count: '.count($productsRaw));
        if (count($productsRaw) > 0) {
            $this->command->info('First raw product keys: '.json_encode(array_keys($productsRaw[0])));
        }
        $zip->close();

        // 2. Build Category and Brand lookup dictionaries
        $categoriesMap = [];
        $brandsMap = [];
        foreach ($lookups as $item) {
            if ($item['kind'] === 'category') {
                $categoriesMap[$item['id']] = [
                    'name' => trim($item['name']),
                    'slug' => trim($item['slug']) ?: Str::slug($item['name']),
                ];
            } elseif ($item['kind'] === 'brand') {
                $brandsMap[$item['id']] = [
                    'name' => trim($item['name']),
                    'slug' => trim($item['slug']) ?: Str::slug($item['name']),
                ];
            }
        }

        // Ensure a fallback category exists
        $fallbackCategory = Category::firstOrCreate(
            ['slug' => 'digital-goods'],
            [
                'name' => 'Digital Goods',
                'status' => 'active',
                'is_featured' => true,
            ]
        );

        // 3. Ensure all categories from lookups exist in the database
        $categoryDbIds = [];
        foreach ($categoriesMap as $lookupId => $catData) {
            $category = Category::firstOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'status' => 'active',
                    'is_featured' => true,
                ]
            );
            $categoryDbIds[$lookupId] = $category->id;
        }

        // 4. Ensure all brands from lookups exist in the database
        $brandDbIds = [];
        foreach ($brandsMap as $lookupId => $brandData) {
            $brand = Brand::firstOrCreate(
                ['slug' => $brandData['slug']],
                [
                    'name' => $brandData['name'],
                    'status' => 'active',
                ]
            );
            $brandDbIds[$lookupId] = $brand->id;
        }

        // Group variants by product_id
        $variantsByProduct = [];
        foreach ($variantsRaw as $variant) {
            $prodId = $variant['product_id'] ?? '';
            if ($prodId !== '') {
                $variantsByProduct[$prodId][] = $variant;
            }
        }

        // 5. Ensure product images directory exists in public storage
        $publicProductImgDir = storage_path('app/public/product_image');
        if (! File::exists($publicProductImgDir)) {
            File::makeDirectory($publicProductImgDir, 0755, true);
        }

        // 6. Filter and seed digital products
        $digitalProducts = array_filter($productsRaw, function ($row) {
            return ($row['product_type'] ?? '') === 'digital';
        });

        $this->command->info('Found '.count($digitalProducts).' digital products to seed.');

        DB::beginTransaction();
        try {
            $seededCount = 0;
            $plansCount = 0;

            foreach ($digitalProducts as $row) {
                $originalId = $row['id'] ?? '';
                $name = trim($row['name'] ?? '');
                $slug = trim($row['slug'] ?? '') ?: Str::slug($name);

                // Resolve Category ID
                $catLookupId = $row['category_id'] ?? '';
                $categoryId = $categoryDbIds[$catLookupId] ?? $fallbackCategory->id;

                // Resolve Brand ID
                $brandLookupId = $row['brand_id'] ?? '';
                $brandId = $brandDbIds[$brandLookupId] ?? null;

                // Image relative path in public storage: product_image/filename.png
                $rawThumb = trim($row['thumbnail'] ?? '');
                $thumbnailPath = null;
                $galleryPaths = [];

                if ($rawThumb !== '') {
                    $thumbnailPath = 'product_image/'.$rawThumb;
                    $galleryPaths = ['product_image/'.$rawThumb];
                }

                // Features array
                $features = null;
                if (! empty($row['features'])) {
                    $decodedFeatures = json_decode($row['features'], true);
                    if (is_array($decodedFeatures)) {
                        $features = $decodedFeatures;
                    }
                }

                // Prices
                $price = ! empty($row['price']) ? (float) $row['price'] : 0.00;
                $salePrice = ! empty($row['sale_price']) ? (float) $row['sale_price'] : null;
                $hasPlans = ! empty($row['has_plans']) && (string) $row['has_plans'] === '1';

                // Rating & Reviews
                $rating = ! empty($row['rating']) ? (float) $row['rating'] : 4.80;
                $reviewsCount = ! empty($row['reviews_count']) ? (int) $row['reviews_count'] : 5;

                // Delivery information
                $deliveryType = trim($row['digital_delivery_type'] ?? '') ?: 'account_credentials';
                $instructions = trim($row['access_instructions'] ?? '') ?: 'Credentials and access guidelines will be delivered to your registered email and user order dashboard.';

                $product = Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'product_type' => 'digital',
                        'category_id' => $categoryId,
                        'brand_id' => $brandId,
                        'thumbnail' => $thumbnailPath,
                        'gallery' => $galleryPaths,
                        'short_description' => trim($row['short_description'] ?? '') ?: $name,
                        'description' => trim($row['description'] ?? '') ?: (trim($row['short_description'] ?? '') ?: $name),
                        'features' => $features,
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'status' => 'active',
                        'is_featured' => ! empty($row['is_featured']) && (string) $row['is_featured'] === '1',
                        'rating' => $rating,
                        'reviews_count' => $reviewsCount,
                        'stock_quantity' => 9999,
                        'low_stock_threshold' => 10,
                        'shipping_charge' => 0.00,
                        'digital_delivery_type' => $deliveryType,
                        'access_instructions' => $instructions,
                        'download_link' => trim($row['download_link'] ?? '') ?: null,
                        'download_file' => trim($row['download_file'] ?? '') ?: null,
                        'default_validity' => trim($row['default_validity'] ?? '') ?: null,
                        'has_plans' => $hasPlans,
                    ]
                );

                $seededCount++;

                // 7. Seed product plans / variations
                $productVariants = $variantsByProduct[$originalId] ?? [];
                if (! empty($productVariants)) {
                    // Remove old plans to allow fresh idempotent seeding
                    ProductPlan::where('product_id', $product->id)->delete();

                    $sortOrder = 1;
                    $minPlanPrice = null;

                    foreach ($productVariants as $vIndex => $variant) {
                        $optionsRaw = $variant['options'] ?? '';
                        $options = json_decode($optionsRaw, true);

                        // Build Plan Name from Options
                        $planName = '';
                        $validity = null;

                        if (is_array($options) && ! empty($options)) {
                            // Detect validity if specified in Duration
                            if (isset($options['Duration'])) {
                                $validity = $options['Duration'];
                            }

                            // Join option parts nicely (e.g., "1 Month - Pro")
                            $planName = implode(' - ', array_values($options));
                        }

                        if ($planName === '') {
                            $planName = 'Standard Plan '.($vIndex + 1);
                        }

                        $vPrice = ! empty($variant['price']) ? (float) $variant['price'] : 0.00;
                        $vSalePrice = ! empty($variant['sale_price']) ? (float) $variant['sale_price'] : null;

                        // Fallback to base product price if variation price is omitted
                        if ($vPrice <= 0 && $price > 0) {
                            $vPrice = $price;
                        }

                        $isDefault = ($sortOrder === 1);

                        ProductPlan::create([
                            'product_id' => $product->id,
                            'name' => $planName,
                            'validity' => $validity,
                            'price' => $vPrice,
                            'sale_price' => $vSalePrice,
                            'stock_quantity' => 999,
                            'sku' => trim($variant['sku'] ?? '') ?: null,
                            'is_default' => $isDefault,
                            'sort_order' => $sortOrder++,
                        ]);

                        $plansCount++;

                        if ($minPlanPrice === null || ($vPrice > 0 && $vPrice < $minPlanPrice)) {
                            $minPlanPrice = $vPrice;
                        }
                    }

                    // Update product base price to match lowest active plan price
                    if ($minPlanPrice !== null && $minPlanPrice > 0 && ($product->price <= 0 || $minPlanPrice < $product->price)) {
                        $product->update([
                            'price' => $minPlanPrice,
                            'has_plans' => true,
                        ]);
                    }
                } elseif ($hasPlans && $price > 0) {
                    // If product is marked as has_plans but no variants in sheet, create standard default plan
                    ProductPlan::firstOrCreate(
                        ['product_id' => $product->id, 'name' => '1 Month Access'],
                        [
                            'validity' => '1 Month',
                            'price' => $price,
                            'sale_price' => $salePrice,
                            'stock_quantity' => 999,
                            'is_default' => true,
                            'sort_order' => 1,
                        ]
                    );
                    $plansCount++;
                }
            }

            DB::commit();

            $this->command->info("Successfully seeded {$seededCount} digital products with {$plansCount} plans/variations!");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error("Seeding failed: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Parse an OpenXML sheet inside the zip archive without requiring external libraries.
     *
     * @return array<int, array<string, string>>
     */
    private function parseSheet(ZipArchive $zip, string $sheetPath): array
    {
        $content = $zip->getFromName($sheetPath);
        if (! $content) {
            return [];
        }

        $xml = simplexml_load_string($content);
        if ($xml === false) {
            return [];
        }

        $xml->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $rows = $xml->xpath('//x:sheetData/x:row');
        if (! $rows) {
            return [];
        }

        $headers = [];
        $data = [];

        foreach ($rows as $rowIndex => $row) {
            $row->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $cells = [];
            foreach ($row->xpath('x:c') as $c) {
                $c->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                $ref = (string) $c['r'];
                $colLetter = preg_replace('/[0-9]/', '', $ref);

                $val = '';
                $is = $c->xpath('x:is/x:t');
                if (! empty($is)) {
                    $val = (string) $is[0];
                } else {
                    $v = $c->xpath('x:v');
                    if (! empty($v)) {
                        $val = (string) $v[0];
                    }
                }

                $cells[$colLetter] = $val;
            }

            if ($rowIndex === 0) {
                $headers = $cells;
            } else {
                $namedRow = [];
                foreach ($headers as $col => $name) {
                    $namedRow[$name] = $cells[$col] ?? '';
                }
                $data[] = $namedRow;
            }
        }

        return $data;
    }
}
