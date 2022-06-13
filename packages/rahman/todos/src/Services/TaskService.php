<?php
namespace Rahman\Todos\Services;

use Illuminate\Support\Facades\Auth;
use DB;
use Rahman\Todos\Resources\TaskResource;
use Rahman\Todos\Models\Task;

/**
 * @group Tasks
 * Provide the required data for TaskController
 */
class TaskService {

    /**
     * Get list of tasks with count of labels have this task and group the labels of each task
     * 
     * @return \Illuminate\Http\Response
     */
    public function tasksList(){
        $results = DB::table('tasklabels')
        ->selectRaw("count(if(tasks.user_id = ".Auth::id().",tasks.id, null)) as user_label_tasks
        , tasks.id as taskId, tasks.title, tasks.description, tasks.user_id, group_concat(tasklabels.label_id) ")
         ->leftjoin('tasks', 'tasklabels.task_id', '=', 'tasks.id')
         ->groupBy('tasks.id')
        ->get();

        return json_decode(json_encode($results), true);
    }

}