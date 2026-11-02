<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $activityLogs = ActivityLog::paginate(15);
        return view('activity-logs.index', [
            'activityLogs' => $activityLogs,
        ]);
    }

    public function show(ActivityLog $activityLog): View
    {
        return view('activity-logs.show', [
            'activityLog' => $activityLog,
        ]);
    }
}
