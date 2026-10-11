<?php

use App\Http\Controllers\Admin\AppearanceController as AdminAppearanceController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SmtpSettingController as AdminSmtpSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public Store Pages
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/product/{slug?}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

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
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('brands', AdminBrandController::class)->except(['show']);
    Route::resource('users', AdminUserController::class)->except(['show']);

    Route::get('/appearance/header', [AdminAppearanceController::class, 'header'])->name('appearance.header');
    Route::put('/appearance/header', [AdminAppearanceController::class, 'updateHeader'])->name('appearance.header.update');
    Route::put('/appearance/banners/settings', [AdminBannerController::class, 'updateSettings'])->name('appearance.banners.settings.update');
    Route::put('/appearance/banners/trust-features', [AdminBannerController::class, 'updateTrustFeatures'])->name('appearance.banners.trust-features.update');
    Route::post('/appearance/banners/{banner}/toggle-status', [AdminBannerController::class, 'toggleStatus'])->name('appearance.banners.toggle-status');
    Route::resource('appearance/banners', AdminBannerController::class, [
        'as' => 'appearance',
    ])->except(['show']);
    Route::get('/appearance/footer', [AdminAppearanceController::class, 'footer'])->name('appearance.footer');
    Route::put('/appearance/footer', [AdminAppearanceController::class, 'updateFooter'])->name('appearance.footer.update');

    Route::get('/settings/smtp', [AdminSmtpSettingController::class, 'edit'])->name('settings.smtp');
    Route::put('/settings/smtp', [AdminSmtpSettingController::class, 'update'])->name('settings.smtp.update');
    Route::post('/settings/smtp/test', [AdminSmtpSettingController::class, 'test'])->name('settings.smtp.test');
});

// User Account Dedicated Routes (Protected by auth and RoleCheck:user)
Route::middleware(['auth', 'RoleCheck:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [UserDashboardController::class, 'orders'])->name('orders');
    Route::get('/downloads', [UserDashboardController::class, 'downloads'])->name('downloads');
    Route::get('/addresses', [UserDashboardController::class, 'addresses'])->name('addresses');
    Route::get('/account-details', [UserDashboardController::class, 'accountDetails'])->name('account-details');
    Route::post('/account-details', [UserDashboardController::class, 'updateAccountDetails'])->name('account-details.update');
    Route::get('/coupons', [UserDashboardController::class, 'coupons'])->name('coupons');
    Route::get('/rewards', [UserDashboardController::class, 'rewards'])->name('rewards');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');
});
