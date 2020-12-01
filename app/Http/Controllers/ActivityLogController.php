<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index() {
        $logs = ActivityLog::latest()->paginate(15);

        return view('setttings.activity_logs.index')->with('logs', $logs);
    }
    public function store($user_id, $model_type, $model_id, $model_name, $action, $notes = null) {
        $log = new ActivityLog();
        $log->user_id = $user_id;
        $log->model_type = $model_type;
        $log->model_id = $model_id;
        $log->model_name = $model_name;
        $log->action = $action;
        $log->save();
    }
}
