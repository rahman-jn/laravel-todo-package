<?php

use Rahman\Todos\Controllers\TaskController;
use Rahman\Todos\Controllers\LabelController;

// Route::get('todos2', 
//   'Rahman\Todos\TodoController@index');

//   Route::get('todos', [TodoController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::apiResources([
        'tasks' => 'Rahman\Todos\Controllers\TaskController',
        'labels' => 'Rahman\Todos\Controllers\LabelController',
    ]);
    Route::post('tasks/update/', [TaskController::class, 'update']);
});
    // Route::post('tasks', [TaskController::class, 'store']);
    // Route::post('labels', [LabelController::class, 'store']);