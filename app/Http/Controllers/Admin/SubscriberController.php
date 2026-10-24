<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    /**
     * Display a listing of subscribers with search and status filtering.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Subscriber::query()->latest();

        if ($search) {
            $query->where('email', 'like', "%{$search}%");
        }

        if ($status && in_array($status, ['active', 'inactive'], true)) {
            $query->where('status', $status);
        }

        $subscribers = $query->paginate(20)->withQueryString();

        $totalCount = Subscriber::count();
        $activeCount = Subscriber::where('status', 'active')->count();
        $inactiveCount = Subscriber::where('status', 'inactive')->count();

        return view('admin.subscribers.index', compact(
            'subscribers',
            'search',
            'status',
            'totalCount',
            'activeCount',
            'inactiveCount'
        ));
    }

    /**
     * Toggle or update subscriber status.
     */
    public function toggleStatus(Subscriber $subscriber): RedirectResponse
    {
        $newStatus = $subscriber->status === 'active' ? 'inactive' : 'active';
        $subscriber->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Subscriber status updated to {$newStatus}.");
    }

    /**
     * Remove the specified subscriber from storage.
     */
    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()->back()->with('success', 'Subscriber removed successfully.');
    }
}
