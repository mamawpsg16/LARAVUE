<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function getMatchedUsers(Request $request){
        $task = Task::find($request->input('task_id'));
        $user_ids = $task->users()->where('users.id', '!=', auth()->id())->pluck('users.id')->toArray();
        
        $match_users = User::select('username')
                ->whereIn('id',$user_ids)
                ->where('username', 'like', '%'.$request->user_match.'%')
                ->orWhere('email', 'like', '%'.$request->user_match)
                ->get();
                
        return response()->json($match_users);
    }
    
}
