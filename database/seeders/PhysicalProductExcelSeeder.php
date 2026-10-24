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

class PhysicalProductExcelSeeder extends Seeder
{
    /**
     * Run the database seeds for physical products from Physical_Products.xlsx.
     */
    public function run(): void
    {
        $xlsxPath = storage_path('app/public/Physical_Products.xlsx');
        if (! file_exists($xlsxPath)) {
            $this->command->error("Excel file not found at: {$xlsxPath}");

            return;
        }

        $this->command->info('Opening Physical_Products.xlsx...');
        $zip = new ZipArchive;
        if ($zip->open($xlsxPath) !== true) {
            $this->command->error('Unable to open Physical_Products.xlsx via ZipArchive.');

            return;
        }

        // 1. Read Sheets: Products (sheet1), Variants (sheet2), Specifications (sheet3), Lookups (sheet5)
        $lookups = $this->parseSheet($zip, 'xl/worksheets/sheet5.xml');
        $productsRaw = $this->parseSheet($zip, 'xl/worksheets/sheet1.xml');
        $variantsRaw = $this->parseSheet($zip, 'xl/worksheets/sheet2.xml');
        $specsRaw = $this->parseSheet($zip, 'xl/worksheets/sheet3.xml');
        $zip->close();

        $this->command->info('Raw physical products parsed: '.count($productsRaw));
        $this->command->info('Raw variants parsed: '.count($variantsRaw));
        $this->command->info('Raw specifications parsed: '.count($specsRaw));

        // 2. Build Category and Brand lookup dictionaries
        $categoriesMap = [];
        $brandsMap = [];
        foreach ($lookups as $item) {
            $kind = $item['kind'] ?? '';
            $id = $item['id'] ?? '';
            $name = trim($item['name'] ?? '');
            $slug = trim($item['slug'] ?? '') ?: Str::slug($name);

            if ($kind === 'category' && $id !== '') {
                $categoriesMap[$id] = [
                    'name' => $name,
                    'slug' => $slug,
                ];
            } elseif ($kind === 'brand' && $id !== '') {
                $brandsMap[$id] = [
                    'name' => $name,
                    'slug' => $slug,
                ];
            }
        }

        // Fallback Category
        $fallbackCategory = Category::firstOrCreate(
            ['slug' => 'gadget-electronics'],
            [
                'name' => 'Gadget & Electronics',
                'status' => 'active',
                'is_featured' => true,
                'delivery_charge_inside_dhaka' => 60,
                'delivery_charge_outside_dhaka' => 120,
            ]
        );

        // Ensure all categories from lookups exist in database
        $categoryDbIds = [];
        foreach ($categoriesMap as $lookupId => $catData) {
            $category = Category::firstOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'status' => 'active',
                    'is_featured' => true,
                    'delivery_charge_inside_dhaka' => 60,
                    'delivery_charge_outside_dhaka' => 120,
                ]
            );
            $categoryDbIds[$lookupId] = $category->id;
        }

        // Ensure all brands from lookups exist in database
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

        // 3. Group specifications by product ID
        $specsByProduct = [];
        foreach ($specsRaw as $spec) {
            $pid = trim($spec['product_id'] ?? '');
            $key = trim($spec['specification'] ?? '');
            $val = trim($spec['value'] ?? '');
            if ($pid !== '' && $key !== '' && $val !== '') {
                $specsByProduct[$pid][] = "{$key}: {$val}";
            }
        }

        // 4. Group variants by product ID
        $variantsByProduct = [];
        foreach ($variantsRaw as $variant) {
            $pid = trim($variant['product_id'] ?? '');
            if ($pid !== '') {
                $variantsByProduct[$pid][] = $variant;
            }
        }

        // 5. Setup Image Storage Paths
        // Source images are located in /storage/app/public/physical_products or /storage/app/public/physicl_products
        $sourceImagesDir = storage_path('app/public/physical_products');
        if (! File::isDirectory($sourceImagesDir)) {
            $sourceImagesDir = storage_path('app/public/physicl_products');
        }

        $destImagesDir = storage_path('app/public/products');
        if (! File::isDirectory($destImagesDir)) {
            File::makeDirectory($destImagesDir, 0755, true);
        }

        // 6. Iterate through products and seed
        $seededCount = 0;
        DB::beginTransaction();

        try {
            foreach ($productsRaw as $row) {
                $originalId = trim($row['id'] ?? '');
                $name = trim($row['name'] ?? '');
                if ($name === '') {
                    continue;
                }

                // Clean slug: decode url encoded characters if any
                $rawSlug = trim($row['slug'] ?? '');
                $decodedSlug = urldecode($rawSlug);
                $slug = Str::slug($decodedSlug);
                if (empty($slug)) {
                    $slug = Str::slug($name);
                }

                // Map category
                $lookupCatId = trim($row['category_id'] ?? '');
                $categoryId = $categoryDbIds[$lookupCatId] ?? $fallbackCategory->id;

                // Map brand
                $lookupBrandId = trim($row['brand_id'] ?? '');
                $brandId = $brandDbIds[$lookupBrandId] ?? null;

                // Copy image to products directory if exists
                $rawThumbnail = trim($row['thumbnail'] ?? '');
                $thumbnailPath = null;
                $galleryPaths = [];

                if ($rawThumbnail !== '') {
                    $sourceFile = $sourceImagesDir.DIRECTORY_SEPARATOR.$rawThumbnail;
                    if (File::exists($sourceFile)) {
                        $targetFileName = 'physical_'.$rawThumbnail;
                        $destFile = $destImagesDir.DIRECTORY_SEPARATOR.$targetFileName;
                        if (! File::exists($destFile)) {
                            File::copy($sourceFile, $destFile);
                        }
                        $thumbnailPath = 'products/'.$targetFileName;
                        $galleryPaths = [$thumbnailPath];
                    } else {
                        // Fallback check directly in products folder
                        $targetFileName = 'physical_'.$rawThumbnail;
                        if (File::exists($destImagesDir.DIRECTORY_SEPARATOR.$targetFileName)) {
                            $thumbnailPath = 'products/'.$targetFileName;
                            $galleryPaths = [$thumbnailPath];
                        }
                    }
                }

                // Features array
                $features = [];
                if (! empty($row['features'])) {
                    $decodedFeatures = json_decode($row['features'], true);
                    if (is_array($decodedFeatures)) {
                        $features = $decodedFeatures;
                    }
                }

                // Append specs to features if available
                $productSpecs = $specsByProduct[$originalId] ?? [];
                if (! empty($productSpecs) && empty($features)) {
                    $features = array_slice($productSpecs, 0, 8);
                }

                // Full description composition: include specifications formatted if description is brief
                $description = trim($row['description'] ?? '');
                if (! empty($productSpecs)) {
                    $specBlock = "\n\nSpecifications:\n• ".implode("\n• ", $productSpecs);
                    if (! str_contains($description, 'Specifications:')) {
                        $description = ($description ? $description : $name).$specBlock;
                    }
                }
                if ($description === '') {
                    $description = trim($row['short_description'] ?? '') ?: $name;
                }

                // Prices
                $price = ! empty($row['price']) ? (float) $row['price'] : 0.00;
                $salePrice = ! empty($row['sale_price']) ? (float) $row['sale_price'] : null;

                // Check variants if price is 0
                $productVariants = $variantsByProduct[$originalId] ?? [];
                if ($price <= 0.00 && ! empty($productVariants)) {
                    $firstVarPrice = ! empty($productVariants[0]['price']) ? (float) $productVariants[0]['price'] : 0.00;
                    if ($firstVarPrice > 0) {
                        $price = $firstVarPrice;
                    }
                }
                if ($price <= 0.00) {
                    $price = 2490.00; // Reasonable default fallback for tech gadgets
                }

                $hasPlans = ! empty($productVariants) || (! empty($row['has_plans']) && (string) $row['has_plans'] === '1');

                // Stock quantity & threshold
                $stockQuantity = ! empty($row['stock_quantity']) ? (int) $row['stock_quantity'] : 50;
                $lowStockThreshold = ! empty($row['low_stock_threshold']) ? (int) $row['low_stock_threshold'] : 5;

                // Physical product specs
                $dimensions = trim($row['dimensions'] ?? '') ?: null;
                $weight = trim($row['weight'] ?? '') ?: null;
                $color = trim($row['color'] ?? '') ?: null;
                $warranty = trim($row['warranty_period'] ?? '') ?: null;
                $packageContents = trim($row['package_contents'] ?? '') ?: null;

                // Ratings & reviews
                $rating = ! empty($row['rating']) ? (float) $row['rating'] : 4.80;
                $reviewsCount = ! empty($row['reviews_count']) ? (int) $row['reviews_count'] : rand(3, 15);

                $product = Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'product_type' => 'physical',
                        'category_id' => $categoryId,
                        'brand_id' => $brandId,
                        'thumbnail' => $thumbnailPath,
                        'gallery' => $galleryPaths,
                        'short_description' => trim($row['short_description'] ?? '') ?: $name,
                        'description' => $description,
                        'features' => ! empty($features) ? $features : null,
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'status' => 'active',
                        'is_featured' => ! empty($row['is_featured']) && (string) $row['is_featured'] === '1',
                        'rating' => $rating,
                        'reviews_count' => $reviewsCount,
                        'stock_quantity' => $stockQuantity,
                        'low_stock_threshold' => $lowStockThreshold,
                        'shipping_charge' => 0.00,
                        'weight' => $weight,
                        'dimensions' => $dimensions,
                        'color' => $color,
                        'warranty_period' => $warranty,
                        'package_contents' => $packageContents,
                        'has_plans' => $hasPlans,
                    ]
                );

                $seededCount++;

                // 7. Seed product plans / variations if available
                if (! empty($productVariants)) {
                    ProductPlan::where('product_id', $product->id)->delete();

                    $sortOrder = 1;
                    $minPlanPrice = null;

                    foreach ($productVariants as $vIndex => $variant) {
                        $optionsRaw = $variant['options'] ?? '';
                        $options = json_decode($optionsRaw, true);

                        $planName = '';
                        if (is_array($options) && ! empty($options)) {
                            $planName = implode(' - ', array_values($options));
                        }

                        if ($planName === '') {
                            $planName = 'Option '.($vIndex + 1);
                        }

                        $vPrice = ! empty($variant['price']) ? (float) $variant['price'] : 0.00;
                        $vSalePrice = ! empty($variant['sale_price']) ? (float) $variant['sale_price'] : null;

                        if ($vPrice <= 0 && $price > 0) {
                            $vPrice = $price;
                        }

                        $isDefault = ($sortOrder === 1);

                        ProductPlan::create([
                            'product_id' => $product->id,
                            'name' => $planName,
                            'validity' => null, // Physical products do not have subscription validity
                            'price' => $vPrice,
                            'sale_price' => $vSalePrice,
                            'stock_quantity' => 50,
                            'sku' => trim($variant['sku'] ?? '') ?: null,
                            'is_default' => $isDefault,
                            'sort_order' => $sortOrder++,
                        ]);

                        if ($minPlanPrice === null || $vPrice < $minPlanPrice) {
                            $minPlanPrice = $vPrice;
                        }
                    }

                    // Keep base price aligned with the lowest starting option
                    if ($minPlanPrice !== null && $minPlanPrice > 0 && $product->price != $minPlanPrice) {
                        $product->update(['price' => $minPlanPrice]);
                    }
                }
            }

            DB::commit();
            $this->command->info("Physical products successfully seeded: {$seededCount} products processed!");
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
