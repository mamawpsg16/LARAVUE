<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\TaskNotification;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    
    public function store(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->only(['user_id', 'comment', 'task_id']), [
            'user_id' => 'required',
            'comment' => 'required',
            'task_id' => 'required'
        ]);
        // dd($request->all(),$request->input('comment'));
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id = Crypt::decryptString($request->input('user_id'));
        $task_comment = TaskComment::create([
            'comment' => $request->input('comment'),
            'task_id' => $request->input('task_id'),
            'user_id' => $user_id
        ]);
        $user = User::find($user_id);
        $auth_user_id = [intval($user_id)];
        $task_assigned_user_ids = Task::find($request->input('task_id'))->users()->wherePivot('notif_enable', 1)->pluck('user_id')->toArray();
        // dd($task_assigned_user_ids,$task->users()->wherePivot('notif_enable', 1)->pluck('user_id'));
        $task = Task::find($request->input('task_id'));
        $others_id = array_diff($task_assigned_user_ids, $auth_user_id);
        $mentionedUsernames = $request->input('mentioned_usernames');
        // dd($mentionedUsernames, empty($mentionedUsernames));
        if($mentionedUsernames){
            $users = User::whereIn('username', $mentionedUsernames)->get();
            // dd($users);
            foreach ($users as $collab_user) {
                $collab_user->notify(new TaskNotification($task, $user->username . ' mentioned you'));
            }
        }
        
        if($others_id && count($mentionedUsernames) === 0){
            // Task::find($request->input('task_id'))->users()->attach($others_id);
            foreach($others_id as $user_id){
                $assignedUser = User::find($user_id);
                $assignedUser->notify(new TaskNotification($task,$user->username.' commented'));
            }
        }

        return response()->json($task_comment);
    }

    public function update(Request $request, TaskComment $task_comment){
        $validator = Validator::make($request->only([ 'comment']), [
            'comment' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],422);
        }

        $task_comment->update(['comment' => $request->comment]);

        return response()->json($task_comment);
    }

    public function destroy(TaskComment $task_comment){
        $task_comment->delete();

        return response()->json(true);
    }
}
