<?php

use App\Http\Controllers\Admin\AffiliateSettingController as AdminAffiliateSettingController;
use App\Http\Controllers\Admin\AppearanceController as AdminAppearanceController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ShippingSettingController as AdminShippingSettingController;
use App\Http\Controllers\Admin\SmtpSettingController as AdminSmtpSettingController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SystemClearController as AdminSystemClearController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController as PublicBlogController;
use App\Http\Controllers\BrandController as PublicBrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController as PublicCategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\User\WithdrawalController as UserWithdrawalController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public Store Pages
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::view('/contact', 'contact')->name('contact');

// Public Blog Routes
Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('blog.show');
Route::get('/newsletter/unsubscribe/{uuid}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::get('/category/{slug}', [PublicCategoryController::class, 'show'])->name('category.show');
Route::get('/brand/{slug}', [PublicBrandController::class, 'show'])->name('brand.show');
Route::get('/product/{slug?}', [ProductController::class, 'show'])->name('product.show');
Route::get('/shop', [ProductController::class, 'search'])->name('search');

// Product Review Route (Requires login)
Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('product.review.store')->middleware('auth');

// Cart & Wishlist Routes (AJAX supported)
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CouponController::class, 'apply'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CouponController::class, 'remove'])->name('cart.coupon.remove');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

// Checkout Routes (Requires Authentication to access or place order)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/calculate', [CheckoutController::class, 'calculate'])->name('checkout.calculate');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
});

// Checkout Public Gateway Callbacks & Success Pages
Route::get('/checkout/callback', [CheckoutController::class, 'dgepayCallback'])->name('checkout.dgepay.callback');
Route::get('/checkout/order-success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/shipping/locations', [ShippingController::class, 'locations'])->name('shipping.locations');
Route::post('/shipping/calculate', [ShippingController::class, 'calculate'])->name('shipping.calculate');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes (Protected by auth and RoleCheck:admin)
Route::middleware(['auth', 'RoleCheck:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products/export', [AdminProductController::class, 'export'])->name('products.export');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('brands', AdminBrandController::class)->except(['show']);
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::resource('coupons', AdminCouponController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class);
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::patch('/orders/{order}/quick-status', [AdminOrderController::class, 'updateQuickStatus'])->name('orders.quick-status');

    // Admin Blog Management Routes
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::resource('categories', AdminBlogCategoryController::class)->except(['show']);
        Route::resource('posts', AdminBlogPostController::class)->except(['show']);
    });

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::patch('/subscriptions/{subscription}/quick-status', [SubscriptionController::class, 'quickStatus'])->name('subscriptions.quick-status');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::patch('/subscriptions/{subscription}/quick-status', [SubscriptionController::class, 'quickStatus'])->name('subscriptions.quick-status');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{order}/invoice', [AdminPaymentController::class, 'invoice'])->name('payments.invoice');

    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

    Route::get('/appearance/header', [AdminAppearanceController::class, 'header'])->name('appearance.header');
    Route::put('/appearance/header', [AdminAppearanceController::class, 'updateHeader'])->name('appearance.header.update');
    Route::put('/appearance/banners/settings', [AdminBannerController::class, 'updateSettings'])->name('appearance.banners.settings.update');
    Route::put('/appearance/banners/trust-features', [AdminBannerController::class, 'updateTrustFeatures'])->name('appearance.banners.trust-features.update');
    Route::put('/appearance/banners/promo-banner', [AdminBannerController::class, 'updatePromoBanner'])->name('appearance.banners.promo.update');
    Route::put('/appearance/banners/tri-promo-grid', [AdminBannerController::class, 'updateTriPromoGrid'])->name('appearance.banners.tri-promo.update');
    Route::put('/appearance/banners/dual-promo-grid', [AdminBannerController::class, 'updateDualPromoGrid'])->name('appearance.banners.dual-promo.update');
    Route::put('/appearance/banners/remini-banner', [AdminBannerController::class, 'updateReminiBanner'])->name('appearance.banners.remini.update');
    Route::post('/appearance/banners/{banner}/toggle-status', [AdminBannerController::class, 'toggleStatus'])->name('appearance.banners.toggle-status');
    Route::resource('appearance/banners', AdminBannerController::class, [
        'as' => 'appearance',
    ])->except(['show']);
    Route::get('/appearance/footer', [AdminAppearanceController::class, 'footer'])->name('appearance.footer');
    Route::put('/appearance/footer', [AdminAppearanceController::class, 'updateFooter'])->name('appearance.footer.update');

    Route::get('/settings/shipping', [AdminShippingSettingController::class, 'index'])->name('settings.shipping');
    Route::put('/settings/shipping/global', [AdminShippingSettingController::class, 'updateGlobal'])->name('settings.shipping.global');
    Route::put('/settings/shipping/categories', [AdminShippingSettingController::class, 'updateCategories'])->name('settings.shipping.categories');

    Route::get('/settings/affiliate', [AdminAffiliateSettingController::class, 'index'])->name('settings.affiliate');
    Route::put('/settings/affiliate/global', [AdminAffiliateSettingController::class, 'updateGlobal'])->name('settings.affiliate.global');
    Route::put('/settings/affiliate/categories', [AdminAffiliateSettingController::class, 'updateCategories'])->name('settings.affiliate.categories');

    Route::get('/settings/smtp', [AdminSmtpSettingController::class, 'edit'])->name('settings.smtp');
    Route::put('/settings/smtp', [AdminSmtpSettingController::class, 'update'])->name('settings.smtp.update');
    Route::post('/settings/smtp/test', [AdminSmtpSettingController::class, 'test'])->name('settings.smtp.test');

    // System Cache Clear Route
    Route::post('/system/clear-cache', [AdminSystemClearController::class, 'clear'])->name('system.clear-cache');

    // Admin Reports Routes
    Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales/export', [AdminReportController::class, 'exportSales'])->name('reports.sales.export');
    Route::get('/reports/customer-growth', [AdminReportController::class, 'customerGrowth'])->name('reports.customer-growth');
    Route::get('/reports/customer-growth/export', [AdminReportController::class, 'exportCustomerGrowth'])->name('reports.customer-growth.export');

    // Admin Subscribers Management Routes
    Route::get('/subscribers', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers/{subscriber}/toggle-status', [AdminSubscriberController::class, 'toggleStatus'])->name('subscribers.toggle-status');
    Route::delete('/subscribers/{subscriber}', [AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');

    // Admin Support Tickets Management Routes
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.status');

    // Admin Notification Routes
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [AdminNotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/{notification}/toggle', [AdminNotificationController::class, 'toggle'])->name('notifications.toggle');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [AdminNotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
});

// User Account Dedicated Routes (Protected by auth and RoleCheck:user)
Route::middleware(['auth', 'RoleCheck:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [UserDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}/invoice', [UserDashboardController::class, 'invoice'])->name('orders.invoice');
    Route::get('/subscriptions', [UserDashboardController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/addresses', [UserDashboardController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [UserDashboardController::class, 'updateAddress'])->name('addresses.update');
    Route::get('/account-details', [UserDashboardController::class, 'accountDetails'])->name('account-details');
    Route::post('/account-details', [UserDashboardController::class, 'updateAccountDetails'])->name('account-details.update');
    Route::get('/withdraw', [UserWithdrawalController::class, 'index'])->name('withdraw');
    Route::post('/withdraw', [UserWithdrawalController::class, 'store'])->name('withdraw.store');
    Route::get('/affiliate', [UserDashboardController::class, 'affiliate'])->name('affiliate');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');

    // User Notification Routes
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [UserNotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/{notification}/toggle', [UserNotificationController::class, 'toggle'])->name('notifications.toggle');
    Route::post('/notifications/mark-all-read', [UserNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [UserNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [UserNotificationController::class, 'destroyAll'])->name('notifications.destroy-all');

    // User Support Ticket Routes
    Route::get('/tickets', [UserTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [UserTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [UserTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [UserTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [UserTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/close', [UserTicketController::class, 'close'])->name('tickets.close');

    // Backward compatibility redirects for legacy URLs
    Route::redirect('/downloads', '/user/subscriptions', 301);
    Route::redirect('/coupons', '/user/withdraw', 301);
    Route::redirect('/rewards', '/user/affiliate', 301);
});
