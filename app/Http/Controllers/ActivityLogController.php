<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')
                        ->latest()
                        ->paginate(20);

        return view('activity-logs.index', compact('logs'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('activity-logs.show', compact('activityLog'));
    }
}