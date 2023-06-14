<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskComment;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StoreTaskRequest;
use App\Notifications\TaskNotification;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Validator;

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
        // Retrieve the user based on the decrypted user_id
        $user = User::findOrFail($user_id);
        // Get the task_id from tasks of the user authenticate
        $task_ids = $user->tasks()->pluck('task_id')->toArray();

        // Retrieve tasks with users and their notif_enable values for the specified user
        $tasks = Task::with(['users' => function ($query) use ($user_id) {
            $query->where('user_id', $user_id)->withPivot('notif_enable');
        }]);

        // Filter tasks based on user's email/role 
        if ($user->email !== 'admin@admin.com') {
            $tasks->whereIn('id', $task_ids);
        }

        $sortBy = $request->query('sortBy');
        if ($sortBy !== 'default') {
            // Sorting logic
            $sortField = ($sortBy === 'due_date') ? 'due_date' : 'title';
            $sortDirection = $request->query('sort', 'asc');
            $tasks->orderBy($sortField, $sortDirection);
        }

        // Get the tasks and assign notif_enable values
        $tasks = $tasks->get()->each(function ($task) use ($user_id) {
            $task->notif_enable = $task->users->firstWhere('pivot.user_id', $user_id)->pivot->notif_enable ?? null;
        });

        // Eager load users again after modifying notif_enable
        $tasks->load(['users']);

        // Return the tasks as a JSON response
        return response()->json($tasks, 200);
    }





    public function store(StoreTaskRequest $request)
    {
        // dd($request->all());
        // $dueDate = $request->input('due_date');
        // $dateTime = new DateTime($dueDate);
        // $dateTime->modify('+1 day');
        // $date = $dateTime->format('Y-m-d');
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => date('Y-m-d', strtotime($request->input('due_date'))),
        ]);

        if ($request->input('user_ids')) {
            $task->users()->attach($request->input('user_ids'));
            foreach ($request->input('user_ids') as $user_id) {
                $assignedUser = User::find($user_id);
                $assignedUser->notify(new TaskNotification($task));
            }
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
        $task->load('users', 'comments.user');

        $groupedUsers = $task->users->groupBy('username')->toArray();
        // dd($groupedUsers);
        // $flatten =  Arr::flatten($groupedUsers);
        // $uniqueUsers = collect($groupedUsers)->unique('id')->values()->all();
        // dd($uniqueUsers);
        $task->users = $groupedUsers;

        return response()->json($task);
    }





    public function update(UpdateTaskRequest $request, Task $task)
    {
        // dd($request->all());
        // dd(array_intersect($request->input('user_ids'),$task->users->pluck('id')->toArray()));
        $delete_user_ids = array_diff($task->users()->pluck('user_id')->toArray(), $request->input('user_ids'));
        // $user_ids = array_values($shit);
        $remove_users = DB::table('task_user')
            ->where('task_id', $task->id)
            ->whereIn('user_id', $delete_user_ids)
            ->delete();

        $new_users = array_diff($request->input('user_ids'), $task->users()->pluck('user_id')->toArray());
        // dd($request->input('user_id'),$task->user_id);
        // dd($request->input('user_id') && $task->user_id != $request->input('user_id'));
        // $old_user_id =  $task->user_id;
        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => date('Y-m-d', strtotime($request->input('due_date')))
        ]);

        // $task->users()->detach();

        // dd($request->input('user_id') && $old_user_id != $request->input('user_id'));
        if ($request->input('user_ids') &&  $new_users) {
            $task->users()->attach($new_users);
            // dd($task->users()->wherePivot('notif_enable', 1)->pluck('user_id'));
            foreach ($new_users as $user_id) {
                $assignedUser = User::find($user_id);
                $assignedUser->notify(new TaskNotification($task));
            }
        }

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        $task->users()->detach();
        return response()->json($task);
    }

    public function updateTaskStatus(Request $request)
    {

        $task = Task::where('id', $request->input('id'))->update(['status' => intval($request->input('status'))]);
        return response()->json($task);
    }
}
