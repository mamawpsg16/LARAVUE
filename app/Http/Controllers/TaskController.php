<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Jobs\SendTaskAssignedNotification;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Crypt::decryptString($request->query('user_id'));
        $user = User::where('id',$user_id)->first();
        // dd($user->email);
        $query = Task::query();

        // Filtering logic
        $filter = $request->query('filter');
        if($filter != 'all'){
            $query->where('status', $filter );
        }
        
        // Sorting logic
        $sortBy = $request->query('sortBy');
        if($sortBy != 'default'){
            if ($sortBy === 'due_date') {
                $query->orderBy('due_date');
            } elseif ($sortBy === 'title') {
                $query->orderBy('title');
            }
        }
        if($user->email != 'kevinmensah114@gmail.com'){
            $query->where('user_id',$user->id);
        }
        $tasks = $query->get();
        $tasks->load('user');
        return response()->json($tasks, 200);
    }


    public function store(StoreTaskRequest $request)
    {
        // $dueDate = $request->input('due_date');
        // $dateTime = new DateTime($dueDate);
        // $dateTime->modify('+1 day');
        // $date = $dateTime->format('Y-m-d');
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => date('Y-m-d',strtotime($request->input('due_date'))),
            'user_id'     => $request->input('user_id')
        ]);
        if($request->input('user_id')){
            $assignedUser = User::find($request->input('user_id'));
            SendTaskAssignedNotification::dispatch($assignedUser, $task)->onQueue('notifications');
        }

        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $task->load('user'); 
        return response()->json($task);
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {

        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => date('Y-m-d',strtotime($request->input('due_date'))),
            'user_id'     => $request->input('user_id')
        ]);

        if($request->input('user_id')){
            $assignedUser = User::find( $request->input('user_id'));
    
            SendTaskAssignedNotification::dispatch($assignedUser, $task)->onQueue('notifications');
        }

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json($task);
    }

    public function updateTaskStatus(Request $request){
        
        $task = Task::where('id',$request->input('id'))->update(['status' => intval($request->input('status'))]);
        return response()->json($task);
    }
}
