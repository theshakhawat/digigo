<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use App\Models\UserSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily check at 12:00 AM for subscriptions that have reached their expiration date, mark them as expired, and notify admins.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for active subscriptions that have passed their expiration date...');

        $now = now();

        // Find active subscriptions whose expires_at is not null and is less than or equal to current time
        $expiredSubscriptions = UserSubscription::with(['user', 'order'])
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->get();

        $count = $expiredSubscriptions->count();

        if ($count === 0) {
            $this->info('No subscriptions have expired.');

            return Command::SUCCESS;
        }

        $this->warn("Found {$count} expired subscription(s). Updating status and generating notifications...");

        foreach ($expiredSubscriptions as $sub) {
            $sub->update([
                'status' => 'expired',
            ]);

            $customerName = $sub->user->name ?? 'Customer';
            $customerEmail = $sub->user->email ?? 'N/A';

            // Create Admin Notification
            try {
                AdminNotification::create([
                    'type' => 'subscription_expired',
                    'title' => "Subscription Expired: #{$sub->subscription_code}",
                    'message' => "Subscription for {$sub->product_name} ({$customerName} - {$customerEmail}) has expired.",
                    'link' => route('admin.subscriptions.show', $sub->id),
                    'data' => [
                        'subscription_id' => $sub->id,
                        'subscription_code' => $sub->subscription_code,
                        'product_name' => $sub->product_name,
                        'plan_name' => $sub->plan_name,
                        'user_id' => $sub->user_id,
                        'customer_name' => $customerName,
                        'customer_email' => $customerEmail,
                        'expired_at' => $sub->expires_at?->toDateTimeString(),
                    ],
                    'is_read' => false,
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to create AdminNotification for expired subscription: '.$e->getMessage());
            }

            $this->line(" - Marked #{$sub->subscription_code} ({$sub->product_name}) as expired for {$customerEmail}");
        }

        $this->info("Successfully processed {$count} expired subscription(s).");

        return Command::SUCCESS;
    }
}
