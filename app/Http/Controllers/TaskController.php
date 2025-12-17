<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;



class TaskController extends Controller
{
        public function index()
    {
        $tasks = Task::get();
    return response()->json($tasks);  
  }

public function store(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'status' => 'required|string|max:255',
    ]);

    $task = Task::create([
        'title' => $validated['title'],
        'status' => $validated['status'],
        'user_id' => $user->id,
    ]);

    return response()->json([
        'status' => true,
        'data' => $task
    ]);
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
