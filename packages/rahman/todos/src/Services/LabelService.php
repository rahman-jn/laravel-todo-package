<?php
namespace Rahman\Todos\Services;

use Illuminate\Support\Facades\Auth;
use DB;
use Rahman\Todos\Resources\LabelResource;
use Rahman\Todos\Models\Label;

/**
 * @group Tasks
 * Provide the required data for TaskController
 */
class LabelService {

    /**
     * Display a listing of the label collection belongs to logged-in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function labelsList(){

        $result = DB::table('labels')
        ->selectRaw("count(if(tasks.user_id = ".Auth::id().",tasks.id, null)) as user_label_tasks ,labels.title,labels.id")
        ->leftjoin('tasklabels as tl', 'tl.label_id', '=', 'labels.id')
        ->leftjoin('tasks', 'tl.task_id', '=', 'tasks.id')
        ->groupBy('labels.id','labels.title')
        ->get();

        return json_decode(json_encode($results), true);
    }

}