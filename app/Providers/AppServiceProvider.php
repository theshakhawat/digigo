<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\SupportTicket;
use App\Models\UserNotification;
use App\Models\UserSubscription;
use App\Models\WithdrawalRequest;
use App\Services\MailConfigService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Prevent lazy loading in local development / testing to catch N+1 queries early
        Model::preventLazyLoading(! $this->app->isProduction());

        // Dynamically apply database-driven SMTP / Mailer settings
        $this->app->make(MailConfigService::class)->apply();

        // Share active categories with header/menu in layouts.site & layouts.user
        View::composer(['layouts.site', 'layouts.user'], function ($view) {
            $siteCategories = once(function () {
                return Category::active()
                    ->withCount(['products' => function ($q) {
                        $q->active();
                    }])
                    ->orderByRaw('is_featured DESC, name ASC')
                    ->get();
            });

            $view->with('siteCategories', $siteCategories);
        });

        // Share unread notifications & sidebar counter badges with admin layout (Memoized / Cached briefly)
        View::composer(['layouts.admin', 'admin.*'], function ($view) {
            $unreadNotifications = AdminNotification::latest()->take(10)->get();
            $unreadCount = AdminNotification::where('is_read', false)->count();

            $adminSidebarCounts = Cache::remember('admin_sidebar_counts', 30, function () {
                return [
                    'products' => Product::count(),
                    'total_orders' => Order::count(),
                    'confirmed_orders' => Order::where('order_status', 'confirmed')->count(),
                    'pending_orders' => Order::where('order_status', 'pending')->count(),
                    'completed_orders' => Order::where('order_status', 'completed')->count(),
                    'pending_subscriptions' => UserSubscription::where('status', 'pending')->count(),
                    'paid_orders' => Order::where('payment_status', 'paid')->count(),
                    'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
                    'open_tickets' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
                    'open_tickets' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
                    'coupons' => Coupon::count(),
                    'subscribers' => Subscriber::count(),
                    'blog_posts' => BlogPost::count(),
                    'blog_categories' => BlogCategory::count(),
                    'blog_posts' => \App\Models\BlogPost::count(),
                    'blog_categories' => \App\Models\BlogCategory::count(),
                ];
            });

            $view->with('adminNotifications', $unreadNotifications);
            $view->with('adminUnreadCount', $unreadCount);
            $view->with('adminSidebarCounts', $adminSidebarCounts);
        });

        // Share unread notifications with user layouts
        View::composer(['layouts.user', 'layouts.site', 'user.*'], function ($view) {
            if (auth()->check()) {
                $userNotifications = UserNotification::where('user_id', auth()->id())
                    ->latest()
                    ->take(10)
                    ->get();
                $userUnreadCount = UserNotification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();

                $view->with('userNotifications', $userNotifications);
                $view->with('userUnreadCount', $userUnreadCount);
            } else {
                $view->with('userNotifications', collect());
                $view->with('userUnreadCount', 0);
            }
        });
    }
}
