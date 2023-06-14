<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskComment extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'comment', 'user_id'];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     // Relationship with parent comment
     public function parentComment()
     {
         return $this->belongsTo(TaskComment::class, 'parent_id');
     }
 
     // Relationship with child comments (replies)
     public function childComments()
     {
         return $this->hasMany(TaskComment::class, 'parent_id');
     }
}
