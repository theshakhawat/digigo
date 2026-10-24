<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Store a newly created product review with optional multiple photos.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Check review eligibility
        if (! $product->canUserReview($user)) {
            return back()->with('error', 'You are not eligible to review this product yet. For physical items, your order must be completed/delivered. For digital products, payment must be completed.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max each
        ]);

        // Find qualifying order ID
        $order = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('customer_email', $user->email);
        })->whereHas('items', function ($itemQ) use ($product) {
            $itemQ->where('product_id', $product->id);
        })->latest()->first();

        // Handle multiple image uploads
        $imagePaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    $filename = 'review_'.Str::random(16).'.'.$photo->getClientOriginalExtension();
                    $path = $photo->storeAs('reviews', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }
        }

        // Create or update existing user review for this product
        $existing = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existingImages = $existing->images ?: [];
            $allImages = array_merge($existingImages, $imagePaths);

            $existing->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'images' => ! empty($allImages) ? $allImages : null,
                'status' => 'approved',
            ]);
        } else {
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'order_id' => $order?->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'images' => ! empty($imagePaths) ? $imagePaths : null,
                'status' => 'approved',
            ]);
        }

        // Recalculate average rating & reviews count on product
        $product->recalculateRating();

        return back()->with('success', 'Thank you! Your product review has been submitted successfully.');
    }
}
