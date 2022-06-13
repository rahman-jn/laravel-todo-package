<?php

namespace Rahman\Todos\Models;

use Illuminate\Database\Eloquent\Model;
use Rahman\Todos\Models\Task;

class TaskLabel extends Model
{
    protected $table = "tasklabels";

    protected $fillable = [
        'task_id',
        'label_id'
    ];

    public function task(){
        return $this->belongsTo(Task::class);
    }

    public function label(){
        return $this->belongsTo(Label::class);
    }

}
