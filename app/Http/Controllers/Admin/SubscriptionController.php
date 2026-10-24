<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionActivatedMail;
use App\Models\UserNotification;
use App\Models\UserSubscription;
use App\Services\MailConfigService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of user subscriptions.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = UserSubscription::with(['user', 'order', 'product', 'plan'])->latest();

        if ($status && in_array($status, ['pending', 'active', 'expired', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subscription_code', 'like', "%{$search}%")
                    ->orWhere('product_name', 'like', "%{$search}%")
                    ->orWhere('license_key', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($oq) use ($search) {
                        $oq->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        $subscriptions = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => UserSubscription::count(),
            'pending' => UserSubscription::where('status', 'pending')->count(),
            'active' => UserSubscription::where('status', 'active')->count(),
            'expired' => UserSubscription::where('status', 'expired')->count(),
            'cancelled' => UserSubscription::where('status', 'cancelled')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'counts', 'status', 'search'));
    }

    /**
     * Show single subscription details and management view.
     */
    public function show(UserSubscription $subscription): View
    {
        $subscription->load(['user', 'order.items', 'product', 'plan']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Update subscription fulfillment details and status.
     */
    public function update(Request $request, UserSubscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,active,expired,cancelled'],
            'credentials' => ['nullable', 'string', 'max:5000'],
            'access_instructions' => ['nullable', 'string', 'max:5000'],
            'license_key' => ['nullable', 'string', 'max:500'],
            'login_url' => ['nullable', 'string', 'max:500'],
            'validity' => ['nullable', 'string', 'max:100'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $wasInactive = $subscription->status !== 'active';
        $willBeActive = $validated['status'] === 'active';

        if ($willBeActive && $wasInactive) {
            $validated['activated_at'] = now();
            if (empty($validated['starts_at'])) {
                $validated['starts_at'] = now();
            }
        }

        $subscription->update($validated);

        // If subscription is transitioned to active, notify the customer via in-app UserNotification and Email
        if ($willBeActive && $wasInactive) {
            $this->notifyCustomerOfSubscriptionActivation($subscription);
        }

        return back()->with('success', "Subscription #{$subscription->subscription_code} updated successfully.");
    }

    /**
     * Quickly update subscription status from table.
     */
    public function quickStatus(Request $request, UserSubscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,active,expired,cancelled'],
        ]);

        $wasInactive = $subscription->status !== 'active';
        $willBeActive = $validated['status'] === 'active';

        $updates = ['status' => $validated['status']];
        if ($willBeActive && $wasInactive) {
            $updates['activated_at'] = now();
            if (! $subscription->starts_at) {
                $updates['starts_at'] = now();
            }
        }

        $subscription->update($updates);

        // If subscription is transitioned to active, notify the customer via in-app UserNotification and Email
        if ($willBeActive && $wasInactive) {
            $this->notifyCustomerOfSubscriptionActivation($subscription);
        }

        return back()->with('success', "Status for Subscription #{$subscription->subscription_code} set to {$validated['status']}.");
    }

    /**
     * Send activation notification and email to the subscription customer.
     */
    protected function notifyCustomerOfSubscriptionActivation(UserSubscription $subscription): void
    {
        $subscription->loadMissing(['user', 'order', 'product', 'plan']);

        $targetUser = $subscription->user;
        $recipientEmail = $subscription->target_email ?: ($targetUser?->email ?? $subscription->order?->customer_email);

        // 1. Create In-App UserNotification for logged in customer
        if ($targetUser) {
            try {
                UserNotification::create([
                    'user_id' => $targetUser->id,
                    'type' => 'subscription',
                    'title' => 'Subscription Activated: '.$subscription->product_name,
                    'message' => "Your subscription #{$subscription->subscription_code} is now active. Access credentials and details have been updated in your dashboard.",
                    'link' => route('user.subscriptions'),
                    'data' => [
                        'subscription_id' => $subscription->id,
                        'subscription_code' => $subscription->subscription_code,
                        'product_name' => $subscription->product_name,
                        'plan_name' => $subscription->plan_name,
                        'login_url' => $subscription->login_url,
                        'expires_at' => $subscription->expires_at?->toIso8601String(),
                    ],
                    'is_read' => false,
                ]);
            } catch (Exception $e) {
                Log::error("Failed to create UserNotification for Subscription #{$subscription->subscription_code}: {$e->getMessage()}");
            }
        }

        // 2. Send Email to the customer / target email
        if (! empty($recipientEmail)) {
            try {
                app(MailConfigService::class)->apply();
                Mail::to($recipientEmail)->send(new SubscriptionActivatedMail($subscription));
            } catch (Exception $e) {
                Log::error("Failed to send SubscriptionActivatedMail to {$recipientEmail} for Subscription #{$subscription->subscription_code}: {$e->getMessage()}");
            }
        }
    }

    /**
     * Delete subscription record.
     */
    public function destroy(UserSubscription $subscription): RedirectResponse
    {
        $code = $subscription->subscription_code;
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', "Subscription #{$code} deleted.");
    }
}
