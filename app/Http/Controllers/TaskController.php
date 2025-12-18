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

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجب تسجيل الدخول',
                    'en' => 'Please login'
                ]
            ]);
        }

        $validated = $request->validate([
            'title'  => ['required','string','max:255'],
            'status' => ['required','string','max:255'],
        ]);

        $task = Task::create([
            'title'   => $validated['title'],
            'status'  => $validated['status'],
            'user_id' => $user->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'Successfully'
            ],
            'data' => [
                'id'     => $task->id,
                'title'  => $task->title,
                'status' => $task->status,
                'user_id'=> $task->user_id
            ]
        ]);
    }

    // show
    public function show(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'Not found'
                ]
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id'     => $task->id,
                'title'  => $task->title,
                'status' => $task->status,
                'user_id'=> $task->user_id
            ]
        ]);
    }

// delete
public function destroy(Request $request, $id)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => [
                'ar' => 'يجب تسجيل الدخول',
                'en' => 'Please login'
            ]
        ]);
    }

    $task = Task::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
    if (!$task) {
        return response()->json([
            'status' => false,
            'message' => [
                'ar' => 'غير موجود',
                'en' => 'Not found'
            ]
        ]);
    }
    $task->delete();    
    return response()->json([
        'status' => true,
        'message' => [
            'ar' => 'تم الحذف بنجاح',
            'en' => 'Deleted successfully'
        ]
    ]);
}

// Update a task
public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجب تسجيل الدخول',
                    'en' => 'Please login'
                ]
            ]);
        }

        $task = Task::where('id', $id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'Not found'
                ]
            ]);
        }

        $validated = $request->validate([
            'title'  => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        $task->update($validated);

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم التعديل بنجاح',
                'en' => 'Updated successfully'
            ],
            'data' => [
                'id'      => $task->id,
                'title'   => $task->title,
                'status'  => $task->status,
                'user_id' => $task->user_id
            ]
        ]);
    }
}