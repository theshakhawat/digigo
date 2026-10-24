<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPlan;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch Categories
        $categories = Category::all()->keyBy('name');
        $audioCat = $categories->get('Audio & Sound');
        $smartwatchCat = $categories->get('Smartwatches & Accessories');
        $powerCat = $categories->get('Power & Charging Solutions');
        $computerCat = $categories->get('Computer Peripherals');
        $networkCat = $categories->get('Networking & Smart Security');
        $lightingCat = $categories->get('Lighting & Studio Gear');
        $kitchenCat = $categories->get('Home & Kitchen');
        $mobileCat = $categories->get('Mobile Accessories & Lifestyle');
        $lifestyleCat = $categories->get('Lifestyle');
        $digitalCat = $categories->get('Digital Goods');

        // Fetch Brands
        $brands = Brand::all()->keyBy('name');
        $brandApple = $brands->get('Apple');
        $brandSony = $brands->get('Sony');
        $brandAnker = $brands->get('Anker');
        $brandSamsung = $brands->get('Samsung');
        $brandXiaomi = $brands->get('Xiaomi');
        $brandLogitech = $brands->get('Logitech');
        $brandTplink = $brands->get('TP-Link');
        $brandOpenai = $brands->get('OpenAI');
        $brandCanva = $brands->get('Canva');
        $brandMicrosoft = $brands->get('Microsoft');
        $brandGoogle = $brands->get('Google');
        $brandSpotify = $brands->get('Spotify');
        $brandNetflix = $brands->get('Netflix');

        // =========================================================================
        // 1. DIGITAL PRODUCTS (Subscriptions & License Keys)
        // =========================================================================

        // 1.1 ChatGPT Plus Subscription (Digital Goods - OpenAI)
        $chatgpt = Product::updateOrCreate(
            ['slug' => 'chatgpt-plus-subscription'],
            [
                'name' => 'ChatGPT Plus Subscription Private Account',
                'product_type' => 'digital',
                'category_id' => $digitalCat?->id,
                'brand_id' => $brandOpenai?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Full access to GPT-4o, OpenAI o1, Canvas, Web Browsing, and custom GPTs.',
                'description' => 'Supercharge your daily workflow with official ChatGPT Plus. Instant private mail delivery with 30-day replacement warranty and uninterrupted access.',
                'features' => [
                    'Full access to GPT-4o and advanced reasoning models',
                    'DALL·E 3 High-resolution image generation',
                    'Code Interpreter & Advanced Data Analysis',
                    'Private account with personal password change option',
                ],
                'price' => 1500,
                'sale_price' => 1350,
                'cost_price' => 1100,
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.95,
                'reviews_count' => 142,
                'digital_delivery_type' => 'credentials',
                'access_instructions' => 'Credentials will be sent to your email instantly. Login at chatgpt.com. Contact WhatsApp for quick verification.',
                'default_validity' => '30',
                'has_plans' => true,
            ]
        );
        ProductPlan::where('product_id', $chatgpt->id)->delete();
        ProductPlan::create([
            'product_id' => $chatgpt->id,
            'name' => '1 Month Private Account',
            'validity' => '30',
            'price' => 1500,
            'sale_price' => 1350,
            'stock_quantity' => 100,
            'sku' => 'CGPT-1M',
            'is_default' => true,
            'sort_order' => 1,
        ]);
        ProductPlan::create([
            'product_id' => $chatgpt->id,
            'name' => '3 Months Shared Slot',
            'validity' => '90',
            'price' => 3200,
            'sale_price' => 2850,
            'stock_quantity' => 50,
            'sku' => 'CGPT-3M',
            'is_default' => false,
            'sort_order' => 2,
        ]);

        // 1.2 Canva Pro Subscription (Digital Goods - Canva)
        $canva = Product::updateOrCreate(
            ['slug' => 'canva-pro-invitation-upgrade'],
            [
                'name' => 'Canva Pro Direct Email Activation',
                'product_type' => 'digital',
                'category_id' => $digitalCat?->id,
                'brand_id' => $brandCanva?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Upgrade your own personal email to Canva Pro Edu/Brand Team with unlimited premium assets.',
                'description' => 'Get premium templates, 100M+ stock photos, videos, Magic Eraser, background remover, and cloud storage on your own existing email account.',
                'features' => [
                    'Direct activation link sent to your personal email',
                    '100M+ premium stock photos, videos, and graphics',
                    'One-click background remover & Magic Resize',
                    'Brand Kits, premium fonts, and SVG export',
                ],
                'price' => 350,
                'sale_price' => 299,
                'cost_price' => 150,
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.90,
                'reviews_count' => 96,
                'digital_delivery_type' => 'direct_activation',
                'access_instructions' => 'We will send a team invite link to your checkout email. Click accept to unlock Canva Pro instantly.',
                'default_validity' => '365',
                'has_plans' => true,
            ]
        );
        ProductPlan::where('product_id', $canva->id)->delete();
        ProductPlan::create([
            'product_id' => $canva->id,
            'name' => '1 Year Team Access',
            'validity' => '365',
            'price' => 350,
            'sale_price' => 299,
            'stock_quantity' => 200,
            'sku' => 'CANVA-1Y',
            'is_default' => true,
            'sort_order' => 1,
        ]);
        ProductPlan::create([
            'product_id' => $canva->id,
            'name' => 'Lifetime Team Invite',
            'validity' => '1825',
            'price' => 700,
            'sale_price' => 599,
            'stock_quantity' => 100,
            'sku' => 'CANVA-LIFE',
            'is_default' => false,
            'sort_order' => 2,
        ]);

        // 1.3 Netflix Premium 4K Ultra HD (Digital Goods - Netflix)
        $netflix = Product::updateOrCreate(
            ['slug' => 'netflix-premium-4k-uhd'],
            [
                'name' => 'Netflix Premium 4K Ultra HD Private Profile',
                'product_type' => 'digital',
                'category_id' => $digitalCat?->id,
                'brand_id' => $brandNetflix?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Stream the latest TV series and movies in 4K UHD with personal PIN locked profile.',
                'description' => 'Private profile on an ultra premium UHD account. No household issues, uninterrupted streaming with 24/7 warranty.',
                'features' => [
                    '4K Ultra HD + HDR quality streaming',
                    'Dedicated private profile with PIN lock',
                    'Watch on TV, Mobile, Laptop, or Tablet',
                    'Full replacement warranty during validity',
                ],
                'price' => 380,
                'sale_price' => 330,
                'cost_price' => 220,
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.96,
                'reviews_count' => 210,
                'digital_delivery_type' => 'credentials',
                'access_instructions' => 'Login email, password, and assigned profile name will be sent to your email immediately.',
                'default_validity' => '30',
                'has_plans' => true,
            ]
        );
        ProductPlan::where('product_id', $netflix->id)->delete();
        ProductPlan::create([
            'product_id' => $netflix->id,
            'name' => '1 Month Private Profile',
            'validity' => '30',
            'price' => 380,
            'sale_price' => 330,
            'stock_quantity' => 150,
            'sku' => 'NF-1M',
            'is_default' => true,
            'sort_order' => 1,
        ]);
        ProductPlan::create([
            'product_id' => $netflix->id,
            'name' => '6 Months Private Profile',
            'validity' => '180',
            'price' => 1950,
            'sale_price' => 1800,
            'stock_quantity' => 60,
            'sku' => 'NF-6M',
            'is_default' => false,
            'sort_order' => 2,
        ]);

        // 1.4 Microsoft Office 365 + 1TB Cloud (Digital Goods - Microsoft)
        $office = Product::updateOrCreate(
            ['slug' => 'microsoft-office-365-subscription'],
            [
                'name' => 'Microsoft Office 365 Professional + 1TB OneDrive',
                'product_type' => 'digital',
                'category_id' => $digitalCat?->id,
                'brand_id' => $brandMicrosoft?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1633419461186-7d40a38105ec?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Word, Excel, PowerPoint, Outlook, and 1TB secure OneDrive cloud storage.',
                'description' => 'Install genuine Office apps on up to 5 devices (PC, Mac, Android, iOS) with authentic Microsoft cloud synchronization.',
                'features' => [
                    'Genuine Office apps (Word, Excel, PowerPoint, OneNote)',
                    '1TB personal secure OneDrive cloud storage',
                    'Works on 5 simultaneous devices',
                    'Direct updates from official Microsoft server',
                ],
                'price' => 650,
                'sale_price' => 499,
                'cost_price' => 300,
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.88,
                'reviews_count' => 65,
                'digital_delivery_type' => 'credentials',
                'access_instructions' => 'Custom Microsoft login account with password reset prompt will be emailed. Visit portal.office.com to sign in.',
                'default_validity' => '365',
                'has_plans' => true,
            ]
        );
        ProductPlan::where('product_id', $office->id)->delete();
        ProductPlan::create([
            'product_id' => $office->id,
            'name' => '1 Year 5 Devices + 1TB',
            'validity' => '365',
            'price' => 650,
            'sale_price' => 499,
            'stock_quantity' => 100,
            'sku' => 'OFF-1Y',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        // 1.5 Spotify Premium (Digital Goods - Spotify)
        Product::updateOrCreate(
            ['slug' => 'spotify-premium-individual'],
            [
                'name' => 'Spotify Premium Individual Plan',
                'product_type' => 'digital',
                'category_id' => $digitalCat?->id,
                'brand_id' => $brandSpotify?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1614680376593-902f749f7ffc?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Ad-free high fidelity music streaming with offline downloads on your own account.',
                'description' => 'Upgrade your own Spotify account to Premium. Listen to millions of tracks without any advertisements.',
                'features' => [
                    'Zero ads, unlimited skips, and 320kbps audio',
                    'Offline music download support',
                    'Activated directly on customer email',
                ],
                'price' => 200,
                'sale_price' => 160,
                'cost_price' => 90,
                'status' => 'active',
                'is_featured' => false,
                'rating' => 4.92,
                'reviews_count' => 88,
                'digital_delivery_type' => 'direct_activation',
                'access_instructions' => 'Family invite or direct renewal link will be emailed to your account.',
                'default_validity' => '30',
                'has_plans' => false,
            ]
        );

        // =========================================================================
        // 2. AUDIO & SOUND (Physical - Sony, Apple)
        // =========================================================================

        // 2.1 Sony WH-1000XM5 Wireless Headphones (Audio & Sound - Sony)
        Product::updateOrCreate(
            ['slug' => 'sony-wh-1000xm5-wireless-anc-headphones'],
            [
                'name' => 'Sony WH-1000XM5 Wireless Noise Canceling Headphones',
                'product_type' => 'physical',
                'category_id' => $audioCat?->id,
                'brand_id' => $brandSony?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Industry-leading noise cancellation with two processors and 8 microphones.',
                'description' => 'The Sony WH-1000XM5 rewrites the rules for distraction-free listening. Exceptional sound quality with Hi-Res Audio, crystal clear hands-free calling, and up to 30 hours of battery life.',
                'features' => [
                    'Industry leading Active Noise Cancellation (ANC)',
                    'Magnificent Sound with new Integrated Processor V1',
                    'Crystal clear hands-free calling with 4 beamforming microphones',
                    'Up to 30-hour battery life with quick charging (3 min for 3 hours)',
                ],
                'price' => 38500,
                'sale_price' => 36200,
                'cost_price' => 31000,
                'stock_quantity' => 15,
                'sku' => 'SONY-XM5-BLK',
                'color' => 'Black',
                'warranty_period' => '1 Year Official Warranty',
                'package_contents' => 'Headphones, Carrying Case, 3.5mm Audio Cable, USB-C Charging Cable',
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.98,
                'reviews_count' => 52,
            ]
        );

        // 2.2 Apple AirPods Pro 2nd Gen (Audio & Sound - Apple)
        Product::updateOrCreate(
            ['slug' => 'apple-airpods-pro-2-usb-c'],
            [
                'name' => 'Apple AirPods Pro (2nd Gen) with MagSafe Case (USB-C)',
                'product_type' => 'physical',
                'category_id' => $audioCat?->id,
                'brand_id' => $brandApple?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Up to 2x more Active Noise Cancellation, Adaptive Audio, and Transparency mode.',
                'description' => 'AirPods Pro 2 feature Apple H2 headphone chip delivering smarter noise cancellation, deeply immersive sound, and up to 6 hours of listening time on a single charge.',
                'features' => [
                    'Apple-designed H2 chip for advanced audio performance',
                    'Adaptive Audio dynamically blends Transparency and Active Noise Cancellation',
                    'Personalized Spatial Audio with dynamic head tracking',
                    'Dust, sweat, and water resistant (IP54)',
                ],
                'price' => 28500,
                'sale_price' => 26900,
                'cost_price' => 23500,
                'stock_quantity' => 20,
                'sku' => 'APP-AIRPOD-P2',
                'color' => 'White',
                'warranty_period' => '1 Year Official Apple Warranty',
                'package_contents' => 'AirPods Pro, MagSafe Charging Case (USB-C), Silicone ear tips (4 sizes), USB-C Charge Cable',
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.94,
                'reviews_count' => 74,
            ]
        );

        // =========================================================================
        // 3. SMARTWATCHES & ACCESSORIES (Physical - Apple, Samsung, Xiaomi)
        // =========================================================================

        // 3.1 Apple Watch Series 9 (Smartwatches & Accessories - Apple)
        Product::updateOrCreate(
            ['slug' => 'apple-watch-series-9-gps-45mm'],
            [
                'name' => 'Apple Watch Series 9 GPS 45mm Aluminum Case',
                'product_type' => 'physical',
                'category_id' => $smartwatchCat?->id,
                'brand_id' => $brandApple?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'S9 SiP chip, double tap gesture, brighter Always-On display, and Precision Finding.',
                'description' => 'Your essential companion for a healthy life is now even more powerful. The S9 SiP chip enables a magical way to interact without touching the screen.',
                'features' => [
                    'Brilliant Always-On Retina display up to 2000 nits',
                    'Double tap gesture for one-handed control',
                    'Advanced ECG and Blood Oxygen health sensors',
                    'Fall Detection, Crash Detection, and Emergency SOS',
                ],
                'price' => 52000,
                'sale_price' => 48500,
                'cost_price' => 42000,
                'stock_quantity' => 12,
                'sku' => 'AW9-45-MID',
                'color' => 'Midnight',
                'warranty_period' => '1 Year Apple International Warranty',
                'package_contents' => 'Apple Watch, Sport Band, Magnetic Fast Charger to USB-C Cable',
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.91,
                'reviews_count' => 38,
            ]
        );

        // 3.2 Samsung Galaxy Watch 6 (Smartwatches & Accessories - Samsung)
        Product::updateOrCreate(
            ['slug' => 'samsung-galaxy-watch-6-bt-44mm'],
            [
                'name' => 'Samsung Galaxy Watch 6 Bluetooth 44mm',
                'product_type' => 'physical',
                'category_id' => $smartwatchCat?->id,
                'brand_id' => $brandSamsung?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Personalized sleep coaching, slimmer bezel, crystal clear sapphire glass display.',
                'description' => 'Monitor your wellness day and night with Samsung Galaxy Watch 6. Advanced sleep tracking, heart rate alerts, and personalized HR zones.',
                'features' => [
                    '20% larger display with 30% slimmer bezel',
                    'Sapphire Crystal glass resistance against scratches',
                    'Comprehensive Body Composition Analysis (BIA)',
                    'Fast wireless magnetic charging',
                ],
                'price' => 29000,
                'sale_price' => 26500,
                'cost_price' => 22000,
                'stock_quantity' => 18,
                'sku' => 'GW6-44-GRY',
                'color' => 'Graphite',
                'warranty_period' => '1 Year Brand Warranty',
                'package_contents' => 'Galaxy Watch 6, Sport Band, Wireless Charger Puck',
                'status' => 'active',
                'is_featured' => false,
                'rating' => 4.85,
                'reviews_count' => 29,
            ]
        );

        // =========================================================================
        // 4. POWER & CHARGING SOLUTIONS (Physical - Anker, Xiaomi)
        // =========================================================================

        // 4.1 Anker 737 Power Bank (Power & Charging - Anker)
        Product::updateOrCreate(
            ['slug' => 'anker-737-power-bank-powercore-24k'],
            [
                'name' => 'Anker 737 Power Bank (PowerCore 24K) 140W Fast Charging',
                'product_type' => 'physical',
                'category_id' => $powerCat?->id,
                'brand_id' => $brandAnker?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1609592424364-773df011d1cf?auto=format&fit=crop&w=800&q=80',
                'short_description' => '24,000mAh capacity with ultra-powerful two-way 140W fast charging and smart digital display.',
                'description' => 'Equipped with the latest Power Delivery 3.1 and bi-directional technology to quickly recharge the portable charger or get a 140W ultra-powerful charge for MacBook, iPhone, and Samsung phones.',
                'features' => [
                    '140W High-Speed two-way fast charging',
                    'Smart Digital Display showing output, input, and remaining time',
                    'Huge 24,000mAh capacity charges laptop over 1 full time',
                    'ActiveShield 2.0 temperature monitoring protection',
                ],
                'price' => 14500,
                'sale_price' => 12900,
                'cost_price' => 10500,
                'stock_quantity' => 25,
                'sku' => 'ANK-737-24K',
                'color' => 'Space Gray',
                'warranty_period' => '18 Months Anker Official Replacement Warranty',
                'package_contents' => 'Anker 737 Power Bank, 140W USB-C to USB-C Cable, Welcome Guide, Pouch',
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.97,
                'reviews_count' => 64,
            ]
        );

        // 4.2 Anker Prime 67W GaN Wall Charger (Power & Charging - Anker)
        Product::updateOrCreate(
            ['slug' => 'anker-prime-67w-gan-fast-wall-charger'],
            [
                'name' => 'Anker Prime 67W GaN Wall Charger (3 Ports)',
                'product_type' => 'physical',
                'category_id' => $powerCat?->id,
                'brand_id' => $brandAnker?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Ultra-compact 3-port charger (2 USB-C, 1 USB-A) with ActiveShield 2.0 safety.',
                'description' => 'Charge 3 devices simultaneously with 67W max output. Up to 51% smaller than original 67W single-port chargers.',
                'features' => [
                    'Simultaneous 3-device charging with GaNPrime technology',
                    'Compact and travel-friendly folding plug',
                    'ActiveShield 2.0 dynamic temperature sensor',
                ],
                'price' => 4800,
                'sale_price' => 4200,
                'cost_price' => 3100,
                'stock_quantity' => 35,
                'sku' => 'ANK-67W-GAN',
                'color' => 'Black',
                'warranty_period' => '18 Months Official Warranty',
                'package_contents' => 'Anker Prime 67W Wall Charger, User Guide',
                'status' => 'active',
                'is_featured' => false,
                'rating' => 4.89,
                'reviews_count' => 45,
            ]
        );

        // =========================================================================
        // 5. COMPUTER PERIPHERALS (Physical - Logitech)
        // =========================================================================

        // 5.1 Logitech MX Master 3S Wireless Mouse (Computer Peripherals - Logitech)
        Product::updateOrCreate(
            ['slug' => 'logitech-mx-master-3s-wireless-mouse'],
            [
                'name' => 'Logitech MX Master 3S Performance Wireless Mouse',
                'product_type' => 'physical',
                'category_id' => $computerCat?->id,
                'brand_id' => $brandLogitech?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Quiet clicks, 8K DPI track on glass sensor, and MagSpeed electromagnetic scroll wheel.',
                'description' => 'The ultimate precision mouse remastered. Quiet Clicks deliver tactile feedback with 90% less click noise, paired with an 8,000 DPI track-anywhere optical sensor.',
                'features' => [
                    'Quiet Clicks with 90% reduced click sound',
                    '8,000 DPI optical sensor tracks even on glass',
                    'MagSpeed electromagnetic scroll wheel (1,000 lines per second)',
                    'Connect up to 3 devices via Bluetooth Low Energy or Logi Bolt receiver',
                ],
                'price' => 13500,
                'sale_price' => 11990,
                'cost_price' => 9500,
                'stock_quantity' => 30,
                'sku' => 'LOGI-MX3S-GRY',
                'color' => 'Space Gray',
                'warranty_period' => '1 Year Logitech Official Warranty',
                'package_contents' => 'Mouse, Logi Bolt USB Receiver, USB-C Charging Cable, Documentation',
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.96,
                'reviews_count' => 115,
            ]
        );

        // 5.2 Logitech MX Mechanical Wireless Keyboard (Computer Peripherals - Logitech)
        Product::updateOrCreate(
            ['slug' => 'logitech-mx-mechanical-wireless-keyboard'],
            [
                'name' => 'Logitech MX Mechanical Full Size Wireless Illuminated Keyboard',
                'product_type' => 'physical',
                'category_id' => $computerCat?->id,
                'brand_id' => $brandLogitech?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Low-profile mechanical switches, smart illumination, and multi-OS Bluetooth switching.',
                'description' => 'Experience next-level typing feel with low-profile mechanical tactile quiet switches. Backlit keys illuminate the moment your hands approach.',
                'features' => [
                    'Low-profile tactile quiet mechanical switches',
                    'Smart backlighting adapts to ambient light conditions',
                    'Multi-device and multi-OS compatibility via Easy-Switch',
                    'Up to 15 days battery with backlighting or up to 10 months without',
                ],
                'price' => 19500,
                'sale_price' => 17900,
                'cost_price' => 14500,
                'stock_quantity' => 14,
                'sku' => 'LOGI-MXMECH-KB',
                'color' => 'Graphite',
                'warranty_period' => '1 Year Official Warranty',
                'package_contents' => 'Keyboard, Logi Bolt Receiver, USB-C Cable, User Manual',
                'status' => 'active',
                'is_featured' => false,
                'rating' => 4.91,
                'reviews_count' => 34,
            ]
        );

        // =========================================================================
        // 6. NETWORKING & SMART SECURITY (Physical - TP-Link, Xiaomi)
        // =========================================================================

        // 6.1 TP-Link Archer AX73 WiFi 6 Router (Networking & Smart Security - TP-Link)
        Product::updateOrCreate(
            ['slug' => 'tp-link-archer-ax73-ax5400-wifi-6-router'],
            [
                'name' => 'TP-Link Archer AX73 AX5400 Dual-Band Gigabit Wi-Fi 6 Router',
                'product_type' => 'physical',
                'category_id' => $networkCat?->id,
                'brand_id' => $brandTplink?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=800&q=80',
                'short_description' => '5400 Mbps high-speed WiFi 6 for smooth 8K streaming, gaming, and 6 high-gain antennas.',
                'description' => 'Drastically improved speeds enable streaming, fast downloading, and online gaming all at the same time. Connect 200+ devices with OFDMA and MU-MIMO.',
                'features' => [
                    'Blazing Fast 5400 Mbps Wi-Fi (4804 Mbps on 5 GHz + 574 Mbps on 2.4 GHz)',
                    '6 High-gain antennas with Beamforming for expansive coverage',
                    'HomeShield security with real-time IoT protection and parental controls',
                    'USB 3.0 port enables easy media sharing and private cloud building',
                ],
                'price' => 12500,
                'sale_price' => 10990,
                'cost_price' => 8800,
                'stock_quantity' => 22,
                'sku' => 'TPL-AX73-RTR',
                'color' => 'Black',
                'warranty_period' => '2 Years Official Warranty',
                'package_contents' => 'Wi-Fi Router Archer AX73, Power Adapter, RJ45 Ethernet Cable, Quick Installation Guide',
                'status' => 'active',
                'is_featured' => true,
                'rating' => 4.88,
                'reviews_count' => 49,
            ]
        );

        // 6.2 Xiaomi Smart Security Camera C400 (Networking & Smart Security - Xiaomi)
        Product::updateOrCreate(
            ['slug' => 'xiaomi-smart-camera-c400-2-5k'],
            [
                'name' => 'Xiaomi Smart Camera C400 2.5K Ultra-Clear 360° AI Security',
                'product_type' => 'physical',
                'category_id' => $networkCat?->id,
                'brand_id' => $brandXiaomi?->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1557597774-9d273605dfa9?auto=format&fit=crop&w=800&q=80',
                'short_description' => '4MP 2.5K resolution, AI human detection, 360° pan-tilt-zoom, and two-way voice calling.',
                'description' => 'Keep your home protected with high-definition 2.5K video recording and smart infrared night vision. Two-way real-time voice calls with smart noise reduction.',
                'features' => [
                    'Ultra-clear 2.5K video resolution (2560 x 1440)',
                    '360° horizontal viewing angle and 106° vertical view',
                    'AI human detection filters false alarms',
                    'Works with Google Home & Alexa, micro SD card & cloud storage support',
                ],
                'price' => 4500,
                'sale_price' => 3890,
                'cost_price' => 2900,
                'stock_quantity' => 40,
                'sku' => 'XIAOMI-C400-CAM',
                'color' => 'White',
                'warranty_period' => '6 Months Replacement Warranty',
                'package_contents' => 'Camera, Power Cable, Wall Mount Accessories Pack, User Manual',
                'status' => 'active',
                'is_featured' => false,
                'rating' => 4.86,
                'reviews_count' => 58,
            ]
        );
    }
}
