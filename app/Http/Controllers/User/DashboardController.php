<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AffiliateEarning;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard page.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('user.dashboard', compact('user'));
    }

    /**
     * Show the user orders page.
     */
    public function orders(): View
    {
        $user = Auth::user();
        $orders = Order::with('items')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
            })
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('user', 'orders'));
    }

    /**
     * Show the printable professional invoice for a customer order.
     */
    public function invoice(Order $order): View
    {
        $user = Auth::user();

        // Ensure user is authorized to view this order invoice
        if ($order->user_id !== $user->id && strtolower($order->customer_email) !== strtolower($user->email)) {
            abort(403, 'Unauthorized access to this order invoice.');
        }

        $order->load(['items.product', 'items.plan', 'user']);

        return view('user.invoice', compact('order'));
    }

    /**
     * Show the user subscriptions page.
     */
    public function subscriptions(): View
    {
        $user = Auth::user();
        $subscriptions = UserSubscription::with(['product', 'plan', 'order'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('user.subscriptions', compact('user', 'subscriptions'));
    }

    /**
     * Backward compatible method for downloads.
     */
    public function downloads(): View
    {
        return $this->subscriptions();
    }

    /**
     * Show the user addresses page.
     */
    public function addresses(): View
    {
        $user = Auth::user();
        $address = $user->address;

        return view('user.addresses', compact('user', 'address'));
    }

    /**
     * Store or update the user's single address.
     */
    public function updateAddress(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'division' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:1000'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return back()->with('success', 'Address updated successfully.');
    }

    /**
     * Show the user account details & password edit page.
     */
    public function accountDetails(): View
    {
        $user = Auth::user();

        return view('user.account-details', compact('user'));
    }

    /**
     * Update user account details and optional password.
     */
    public function updateAccountDetails(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        if (! empty($validated['current_password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->save();

        return back()->with('success', 'Account details updated successfully.');
    }

    /**
     * Show the user withdraw page.
     */
    public function withdraw(): View
    {
        $user = Auth::user();
        $coupons = Coupon::currentlyValid()
            ->with(['products:id,name', 'categories:id,name'])
            ->latest('id')
            ->get();

        return view('user.coupons', compact('user', 'coupons'));
    }

    /**
     * Backward compatible method for coupons.
     */
    public function coupons(): View
    {
        return $this->withdraw();
    }

    /**
     * Show the user affiliate dashboard, affiliate link generator, and earnings history.
     */
    public function affiliate(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $affiliateCode = $user->getAffiliateCode();

        // Load affiliate earnings
        $earnings = AffiliateEarning::with('order.items')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Active products for quick link generation
        $products = Product::active()
            ->select('id', 'name', 'slug', 'price', 'product_type', 'thumbnail')
            ->orderBy('name')
            ->get();

        return view('user.rewards', compact('user', 'affiliateCode', 'earnings', 'products'));
    }

    /**
     * Backward compatible method for rewards.
     */
    public function rewards(): View
    {
        return $this->affiliate();
    }

    /**
     * Show the user wishlist page.
     */
    public function wishlist(): View
    {
        $user = Auth::user();

        return view('user.wishlist', compact('user'));
    }
}
