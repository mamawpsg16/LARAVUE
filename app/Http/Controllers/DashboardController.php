<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $tasks_array = [ 
            'pending' => Task::whereStatus(0)->count(),
            'ongoing' => Task::whereStatus(1)->count(),
            'complete' => Task::whereStatus(2)->count(),
            'cancelled' => Task::whereStatus(3)->count(),
            'tasks' => Task::count()
        ];
        $users = User::count();
        return response()->json([
                'users' => $users,
                'tasks' => $tasks_array
            ]);
    }
}
