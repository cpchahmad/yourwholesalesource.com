<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function store($user_id, $model_type, $model_id, $action, $notes = null) {
        $log = new ActivityLog();
        $log->user_id = $user_id;
        $log->model_type = $model_type;
        $log->model_id = $model_id;
        $log->action = $action;
        $log->save();
    }
}
