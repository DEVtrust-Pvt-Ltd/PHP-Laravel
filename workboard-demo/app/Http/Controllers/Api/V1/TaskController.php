<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $service) {}

    public function index(Request $request, Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        return TaskResource::collection($this->service->paginate($project, $request->only('status', 'priority', 'assignee_id', 'search')));
    }

    public function store(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $task = $this->service->create($project, $request->validated(), $request->user());

        return (new TaskResource($this->service->withAssignee($task)))->response()->setStatusCode(201);
    }

    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);

        return new TaskResource($this->service->withAssignee($task));
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $this->service->update($task, $request->validated(), $request->user());

        return new TaskResource($this->service->withAssignee($task));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        $this->service->delete($task);

        return response()->json(status: 204);
    }
}
