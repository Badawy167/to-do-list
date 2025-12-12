<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
        public function index()
    {
        $tasks = Task::get();
    return response()->json($tasks);  
  }



public function show($id)
{
    $task = Task::find($id);

    if (!$task) {
        return response()->json([
            'status' => 'error',
            'message' => 'Task not found'
        ]);
    }

    return response()->json([
        'status' => 'success',
        'data' => $task
    ]);
}

}
