<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of user notifications.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $filter = $request->query('filter', 'all'); // 'all', 'unread', 'read'

        $query = UserNotification::where('user_id', $user->id)->latest();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->paginate(15)->withQueryString();

        $unreadCount = UserNotification::where('user_id', $user->id)->where('is_read', false)->count();
        $totalCount = UserNotification::where('user_id', $user->id)->count();

        return view('user.notifications', compact('notifications', 'unreadCount', 'totalCount', 'filter'));
    }

    /**
     * Mark a single notification as read and redirect to target link.
     */
    public function read(UserNotification $notification, Request $request): RedirectResponse|JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'unread_count' => UserNotification::where('user_id', auth()->id())->where('is_read', false)->count(),
            ]);
        }

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Toggle read/unread status for single notification.
     */
    public function toggle(UserNotification $notification, Request $request): RedirectResponse|JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => ! $notification->is_read]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_read' => $notification->is_read,
                'unread_count' => UserNotification::where('user_id', auth()->id())->where('is_read', false)->count(),
            ]);
        }

        $statusText = $notification->is_read ? 'read' : 'unread';

        return back()->with('success', "Notification marked as {$statusText}.");
    }

    /**
     * Mark all user notifications as read.
     */
    public function markAllRead(Request $request): RedirectResponse|JsonResponse
    {
        UserNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

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
    public function destroy(UserNotification $notification, Request $request): RedirectResponse|JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.',
                'unread_count' => UserNotification::where('user_id', auth()->id())->where('is_read', false)->count(),
            ]);
        }

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Delete all notifications for the authenticated user.
     */
    public function destroyAll(Request $request): RedirectResponse|JsonResponse
    {
        UserNotification::where('user_id', auth()->id())->delete();

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
