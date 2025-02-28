<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\Task;
use Exception;

class TaskController extends Controller
{
    public function storeTask(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title'   => 'required|string|max:255',
            ]);

            $task = Task::create([
                'title'   => $request->title,
            ]);

            return response()->json($task, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }


    public function indexTask(): JsonResponse
    {
        $tasks = Task::orderBy('id', 'desc')->get();
        return response()->json($tasks);
    }


    public function updateTask(Request $request, $id): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);

            $request->validate([
                'is_completed' => 'required|boolean',
            ]);

            $task->is_completed = $request->is_completed;
            $task->save();

            return response()->json($task);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }
}
