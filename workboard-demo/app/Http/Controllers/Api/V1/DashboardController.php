<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $service) {}

    public function __invoke(Request $request): JsonResource
    {
        $data = $this->service->dataFor($request->user());

        return new JsonResource([
            'projects' => $data['summary']['projects'],
            'tasks' => [
                'total' => $data['summary']['todo'] + $data['summary']['in_progress'] + $data['summary']['done'],
                'todo' => $data['summary']['todo'],
                'in_progress' => $data['summary']['in_progress'],
                'done' => $data['summary']['done'],
            ],
            'due_soon' => TaskResource::collection($data['dueSoon']),
        ]);
    }
}
