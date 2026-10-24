<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    /**
     * Unsubscribe a subscriber using their unique UUID.
     */
    public function unsubscribe(Request $request, string $uuid): View
    {
        $subscriber = Subscriber::where('uuid', $uuid)->first();

        if ($subscriber) {
            $subscriber->update(['status' => 'inactive']);
        }

        return view('newsletter.unsubscribed', [
            'subscriber' => $subscriber,
        ]);
    }
}
