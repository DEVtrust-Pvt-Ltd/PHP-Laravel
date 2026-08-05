<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskActivityResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskActivityController extends Controller
{
    public function __construct(private readonly TaskService $service) {}

    public function __invoke(Task $task): AnonymousResourceCollection
    {
        $this->authorize('view', $task);

        return TaskActivityResource::collection($this->service->activities($task));
    }
}
