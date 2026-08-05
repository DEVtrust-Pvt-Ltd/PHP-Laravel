<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard) {}

    public function __invoke(Request $request): Response
    {
        $data = $this->dashboard->dataFor($request->user());
        $data['dueSoon'] = TaskResource::collection($data['dueSoon'])->resolve($request);

        return Inertia::render('Dashboard', $data);
    }
}
