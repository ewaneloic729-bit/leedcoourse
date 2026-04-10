<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class InAppNotificationController extends Controller
{
    public function index(Request $request)
    {
        $rows = collect();

        if (Schema::hasTable('in_app_notifications')) {
            $rows = InAppNotification::where('user_id', optional($request->user())->id)
                ->latest()
                ->paginate(20);
        }

        return view('dashboards.notifications', ['rows' => $rows]);
    }

    public function markRead(Request $request, InAppNotification $notification)
    {
        if ((int) $notification->user_id !== (int) optional($request->user())->id) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return back();
    }
}
