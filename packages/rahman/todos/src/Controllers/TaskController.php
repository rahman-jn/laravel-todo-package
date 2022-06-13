<?php

namespace Rahman\Todos\Controllers;

use Illuminate\Http\Request;
use Rahman\Todos\Requests\TaskRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Notification;
use DB;
use Rahman\Todos\Models\Task;
use Rahman\Todos\Models\Label;
use Rahman\Todos\Models\TaskLabel;
use Rahman\Todos\Resources\TaskResource;
use Rahman\Todos\Services\TaskService;
use Rahman\Todos\Notifications\TaskStatusNotification;
use Rahman\Todos\Events\TaskClosed;

/**
 * @group Tasks
 * API endpoints for managing the tasks
 */
class TaskController extends Controller
{
    //TaskService param for injecting dependency
    protected $taskService;

    /**
     * Constructor for injecting tht dependency
     */
    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userTasks = $this->taskService->tasksList();

        return $userTasks;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Rahman\Todos\Models\TaskRequest  $request
     * @bodyParam title string required Title of the task. Example: Go shopping
     * @bodyParam description string required TitDescriptionle of the task. Example: Buy some milk,fruite and meat
     * @bodyParam task_id int required Id of task for storing in tasklabels table.
     * @bodyParam label_id int required Id of label for storing in tasklabels table.
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    { 

        try{
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => Auth::id()
            ]);

            //Find the labels' ids and insert in tasklabels table
            foreach($request->labels as $labelId){
                TaskLabel::create([
                    'task_id' => $task->id,
                    'label_id' => $labelId
                ]);

            }
            
            return json_decode(json_encode(new TaskResource($task)), true);
        }
        catch(\Illuminate\Database\QueryException $e){
                throw($e);
                //Log the error in application log file with proper message
                Log::channel('application')->error("Duplicated entry ".$request->title." entered!");
                //Error code 400 for bad request
                return response()->json([
                    'message' => 'Duplicated label entered!'
                ], 400);
            
        }
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @bodyParam user_id int required Id of the logged-in user to prevent showing other users task.
     * @bodyParam id int required Id of the task to get from table.
     * @return \Rahman\Todos\Resources\TaskResource
     */
    public function show(int $id)
    {
        $task = Task::where([
            'user_id' => Auth::id(),
            'id' => $id
        ])->first();

        return  json_decode(json_encode(new TaskResource($task)), true);
    }

    /**
     * Updating the given task
     *
     * @param  Rahman\Todos\Requests\TaskRequest; $request
     * @bodyParam user_id int required Id of the logged-in user to prevent showing other users task.
     * @return \Rahman\Todos\Models\Task
     */
    public function update(Request $request) : Task
    {
  
        try{
        $task = Task::where([
            'id' => $request->id,
            'user_id' => Auth::id()
            ])->first();

        $task->update($request->all());
        
        //Send notification if task closed
        if($task->status == 1){
            //Call event for storing the message in log file
            event(new TaskClosed($task));
            //Send Email notification
            Notification::send(Auth::user(), new taskStatusNotification($task));
        }
        return $task;
        }
        catch(Exception $e){
            throw($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Todos\Rahman\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
