<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $projects = $request->user()->projects;

        return response()->json($projects);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $projects = $request->user()->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json($projects, 201);
    }

    public function show(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->id !== $project->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return response()->json($project, 200);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        if ($request->user()->id !== $project->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $project->update($request->validated());
        return response()->json($project, 200);
    }

    public function destroy(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->id !== $project->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $project->delete();
        return response()->json(null, 204);
    }
}
