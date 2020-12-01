<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request) {

        if ($request->has('type_search') && $request->has('role_search')) {
            $logs = ActivityLog::where('user_id', $request->input('role_search'))->where('type', 'LIKE', '%' . $request->input('type_search') . '%')->orderBy('updated_at', 'DESC')->paginate(20);
        }
        else if ($request->has('role_search')) {
            $logs = ActivityLog::whereHas('user', function($q) use ($request) {
                return $q->where('name', 'LIKE', '%' . $request->input('type_search') . '%')->latest()->paginate(20);
            });
        }
        else if ($request->has('type_search')) {
            $logs = ActivityLog::where('model_type', 'LIKE', '%' . $request->input('type_search') . '%')->latest()->paginate(20);
        }
        else {

            $logs = ActivityLog::latest()->paginate(30);
        }

        return view('setttings.activity_logs.index')->with('logs', $logs)->with('search', $request->input('search'));
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
