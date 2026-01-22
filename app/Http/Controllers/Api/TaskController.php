<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index(Request $request, int $taskListId): JsonResponse
    {
        try {
            $tasks = Task::where('task_list_id', $taskListId)
                ->orderBy('created_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tasks,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'task_list_id' => ['required', 'integer', 'exists:task_lists,id'],
                'title' => ['required', 'string', 'max:255'],
                'due_date' => ['nullable', 'date'],
            ]);

            $task = Task::create([
                'task_list_id' => $validated['task_list_id'],
                'title' => $validated['title'],
                'due_date' => $validated['due_date'] ?? null,
                'is_completed' => false,
            ]);

            $task->load('taskList');

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task,
            ]);
        }catch (ValidationException $e){
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error' => $e->errors(),
            ],422);
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $task = Task::with('taskList')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $task,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch task',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => ['sometimes', 'string', 'max:255'],
                'is_completed' => ['sometimes', 'boolean'],
                'due_date' => ['nullable', 'date'],
            ]);

            $task = Task::findOrFail($id);

            $updateData = [];

            if ($request->has('title')){
                $updateData['title'] = $validated['title'];
            }
            if ($request->has('is_completed')){
                $updateData['is_completed'] = $validated['is_completed'];
            }
            if ($request->has('due_date')){
                $updateData['due_date'] = $validated['due_date'];
            }

            $task->update($updateData);
            $task->load('taskList');
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => $task,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task',
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
