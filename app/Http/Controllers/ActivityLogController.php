<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $rows = collect();

        if (Schema::hasTable('activity_logs')) {
            $rows = ActivityLog::where('user_id', optional($request->user())->id)
                ->latest()
                ->paginate(30);
        }

        return view('dashboards.activity-log', ['rows' => $rows]);
    }
}
