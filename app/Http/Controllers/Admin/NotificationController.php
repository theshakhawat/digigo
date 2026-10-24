<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of admin notifications.
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all'); // 'all', 'unread', 'read'

        $query = AdminNotification::latest();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->paginate(20)->withQueryString();

        $unreadCount = AdminNotification::where('is_read', false)->count();
        $totalCount = AdminNotification::count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount', 'totalCount', 'filter'));
    }

    /**
     * Mark a single notification as read or unread and optionally redirect to target link.
     */
    public function read(AdminNotification $notification, Request $request): RedirectResponse|JsonResponse
    {
        $notification->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'unread_count' => AdminNotification::where('is_read', false)->count(),
            ]);
        }

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Toggle read/unread status.
     */
    public function toggle(AdminNotification $notification, Request $request): RedirectResponse|JsonResponse
    {
        $notification->update(['is_read' => ! $notification->is_read]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_read' => $notification->is_read,
                'unread_count' => AdminNotification::where('is_read', false)->count(),
            ]);
        }

        $statusText = $notification->is_read ? 'read' : 'unread';

        return back()->with('success', "Notification marked as {$statusText}.");
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request): RedirectResponse|JsonResponse
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'All notifications have been marked as read.');
    }

    /**
     * Delete a single notification.
     */
    public function destroy(AdminNotification $notification, Request $request): RedirectResponse|JsonResponse
    {
        $notification->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.',
                'unread_count' => AdminNotification::where('is_read', false)->count(),
            ]);
        }

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll(Request $request): RedirectResponse|JsonResponse
    {
        AdminNotification::truncate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications have been deleted.',
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'All notifications have been deleted.');
    }
}
