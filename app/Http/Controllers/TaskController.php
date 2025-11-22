<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return Task::with(['project', 'assignee'])->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:todo,in-progress,done',
        ]);

        $task = Task::create($data);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return $task->load(['project', 'assignee']);
    }

    /**
     * Return a more detailed view of a task (with project, assignee and any relations)
     */
    public function detail(Task $task)
    {
        return $task->load(['project', 'assignee']);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:todo,in-progress,done',
        ]);

        $task->update($data);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}
