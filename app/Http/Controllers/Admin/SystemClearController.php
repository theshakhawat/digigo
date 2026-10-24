<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class SystemClearController extends Controller
{
    /**
     * Clear all application cache, views, routes, events, and compiled files.
     */
    public function clear(Request $request): JsonResponse
    {
        try {
            Artisan::call('optimize:clear');
            Artisan::call('event:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache, views, routes & events cleared successfully!',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear system cache: '.$e->getMessage(),
            ], 500);
        }
    }
}
