<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoveTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;

class TaskMoveController extends Controller
{
    public function __construct(private readonly TaskService $service) {}

    public function __invoke(MoveTaskRequest $request, Task $task): TaskResource
    {
        $this->service->move($task, $request->validated(), $request->user());

        return new TaskResource($this->service->withAssignee($task));
    }
}
