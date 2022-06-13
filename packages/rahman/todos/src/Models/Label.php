<?php

namespace Rahman\Todos\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * group Labels
 * Label model
 */
class Label extends Model
{
    protected $fillable = [
        'title',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function taskLabel(){
        return $this->hasMany(TaskLabel::class);
    }
}
