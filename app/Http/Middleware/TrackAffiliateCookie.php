<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackAffiliateCookie
{
    /**
     * Handle an incoming request and capture ?ref= or ?aff= affiliate code into cookie and session.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ref = $request->query('ref') ?? $request->query('aff');

        if ($ref) {
            $ref = strtoupper(trim($ref));
            // Check if valid active user has this affiliate code
            $referrer = User::where('affiliate_code', $ref)->where('status', 'active')->first();

            if ($referrer) {
                // Store in session
                session(['affiliate_ref' => $ref, 'affiliate_user_id' => $referrer->id]);

                $response = $next($request);

                // Queue 30-day cookie
                return $response->withCookie(cookie('digigo_affiliate_ref', $ref, 60 * 24 * 30));
            }
        }

        return $next($request);
    }
}
