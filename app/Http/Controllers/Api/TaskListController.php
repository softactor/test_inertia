<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    /**
     * Display a listing of the task lists for a specific user.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
            ]);

            $taskLists = TaskList::with('tasks')
                ->where('user_id', $validated['user_id'])
                ->ordered()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $taskLists,
            ]);
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch task lists',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Store a newly created task list in storage.
     */

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            ]);

            $maxPosition = TaskList::max('position') ?? 0;

            $taskList = TaskList::create([
                'user_id' => $validated['user_id'],
                'name' => $validated['name'],
                'position' => $maxPosition + 1,
            ]);

            $taskList->load('tasks');

            return response()->json([
                'success' => true,
                'message' => 'Task list created successfully',
                'data' => $taskList,
            ],201);
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task list',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $taskList = TaskList::with('tasks')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $taskList,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch task list',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $taskList = TaskList::findOrFail($id);
            $taskList->update([
                'name' => $validated['name'],
            ]);

            $taskList->load('tasks');

            return response()->json([
                'success' => true,
                'message' => 'Task list updated successfully',
                'data' => $taskList,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task list',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $taskList = TaskList::findOrFail($id);
            $taskList->delete();
            return response()->json([
                'success' => true,
                'message' => 'Task list deleted successfully',
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task list',
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
