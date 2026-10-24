<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Apply coupon to current cart session.
     */
    public function apply(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
            'division' => ['nullable', 'string'],
            'district' => ['nullable', 'string'],
        ]);

        $code = strtoupper(trim((string) $request->input('code')));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            $msg = 'Invalid coupon code.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        // Authoritatively resolve cart items from DB backend
        $cartItems = CheckoutController::resolveAuthoritativeCartItems();

        if (empty($cartItems)) {
            $msg = 'Cart is empty. Add products to apply coupon.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        $user = Auth::user();
        $email = $user?->email ?? $request->input('email');

        $result = $coupon->calculateDiscount($cartItems, $user, $email);

        if (! $result['valid']) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $result['message']], 422);
            }

            return back()->with('error', $result['message']);
        }

        // Store coupon in session
        session([
            'applied_coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => (float) $result['discount'],
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
            ],
        ]);

        // Calculate refreshed order summary totals from backend
        $summary = CheckoutController::calculateOrderSummary(
            $request->input('division'),
            $request->input('district')
        );

        $successMsg = "Coupon '{$coupon->code}' applied! Discount: ৳".number_format($result['discount'], 2);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'discount' => (float) $result['discount'],
                'code' => $coupon->code,
                'summary' => [
                    'subtotal' => $summary['subtotal'],
                    'discount' => $summary['discount'],
                    'delivery_charge' => $summary['delivery_charge'],
                    'shipping_charge' => $summary['shipping_charge'],
                    'total' => $summary['total'],
                    'subtotal_formatted' => $summary['subtotal_formatted'],
                    'discount_formatted' => $summary['discount_formatted'],
                    'delivery_charge_formatted' => $summary['delivery_charge_formatted'],
                    'shipping_charge_formatted' => $summary['shipping_charge_formatted'],
                    'total_formatted' => $summary['total_formatted'],
                    'is_inside_dhaka' => $summary['is_inside_dhaka'],
                    'is_free_delivery' => $summary['is_free_delivery'],
                ],
            ]);
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Remove applied coupon from session.
     */
    public function remove(Request $request): RedirectResponse|JsonResponse
    {
        session()->forget('applied_coupon');

        // Calculate refreshed order summary totals from backend
        $summary = CheckoutController::calculateOrderSummary(
            $request->input('division'),
            $request->input('district')
        );

        $msg = 'Coupon has been removed.';
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'summary' => [
                    'subtotal' => $summary['subtotal'],
                    'discount' => $summary['discount'],
                    'delivery_charge' => $summary['delivery_charge'],
                    'shipping_charge' => $summary['shipping_charge'],
                    'total' => $summary['total'],
                    'subtotal_formatted' => $summary['subtotal_formatted'],
                    'discount_formatted' => $summary['discount_formatted'],
                    'delivery_charge_formatted' => $summary['delivery_charge_formatted'],
                    'shipping_charge_formatted' => $summary['shipping_charge_formatted'],
                    'total_formatted' => $summary['total_formatted'],
                    'is_inside_dhaka' => $summary['is_inside_dhaka'],
                    'is_free_delivery' => $summary['is_free_delivery'],
                ],
            ]);
        }

        return back()->with('success', $msg);
    }
}
