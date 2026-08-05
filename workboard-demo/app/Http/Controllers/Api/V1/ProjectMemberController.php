<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectMemberRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectMemberController extends Controller
{
    public function __construct(private readonly ProjectService $service) {}

    public function index(Project $project): ProjectResource
    {
        $this->authorize('view', $project);

        return new ProjectResource($this->service->withMembers($project));
    }

    public function store(StoreProjectMemberRequest $request, Project $project): ProjectResource
    {
        $this->service->addExistingMember($project, $request->string('email')->value(), $request->string('role')->value());

        return new ProjectResource($this->service->withMembers($project));
    }

    public function destroy(Project $project, User $user): JsonResponse
    {
        $this->authorize('update', $project);

        $this->service->removeMember($project, $user);

        return response()->json(status: 204);
    }
}
