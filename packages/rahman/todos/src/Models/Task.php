<?php

namespace Rahman\Todos\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function taskLabel(){
        return $this->hasMany(TaskLabel::class);
    }
}
