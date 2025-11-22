<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::with('tasks')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:planning,ongoing,completed',
        ]);

        $project = Project::create($data);

        return response()->json($project, 201);
    }

    public function show(Project $project)
    {
        return $project->load('tasks');
    }

    /**
     * Return detailed project with tasks and their assignees
     */
    public function detail(Project $project)
    {
        return $project->load(['tasks.assignee']);
    }

    /**
     * List tasks for a specific project
     */
    public function tasks(Project $project)
    {
        return $project->tasks()->with('assignee')->paginate(20);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:planning,ongoing,completed',
        ]);

        $project->update($data);

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(null, 204);
    }
}
