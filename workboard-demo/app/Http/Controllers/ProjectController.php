<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectMemberRequest;
use App\Http\Requests\StoreProjectMemberRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskActivityResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $service) {}

    public function index(Request $request): Response
    {
        $projects = $this->service->listFor($request->user(), $request->string('search')->value());

        return Inertia::render('Projects/Index', [
            'projects' => ProjectResource::collection($projects)->resolve($request),
            'filters' => ['search' => $request->string('search')->value()],
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $this->service->create($request->user(), $request->validated());

        return to_route('projects.index')->with('success', 'Project created.');
    }

    public function show(Project $project): Response
    {
        $this->authorize('view', $project);
        $data = $this->service->boardData($project, request()->user());

        return Inertia::render('Projects/Show', [
            'project' => (new ProjectResource($data['project']))->resolve(request()),
            'tasks' => TaskResource::collection($data['tasks'])->resolve(request()),
            'activities' => TaskActivityResource::collection($data['activities'])->resolve(request()),
            'permissions' => $data['permissions'],
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->service->update($project, $request->validated());

        return back()->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $this->service->delete($project);

        return to_route('projects.index')->with('success', 'Project deleted.');
    }

    public function addMember(StoreProjectMemberRequest $request, Project $project): RedirectResponse
    {
        $this->service->addExistingMember($project, $request->string('email')->value(), $request->string('role')->value());

        return back()->with('success', 'Project member saved.');
    }

    public function createMember(CreateProjectMemberRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        $this->service->createMember($project, $data);

        return back()->with('success', 'New member created and added. Share the temporary password securely.');
    }

    public function removeMember(Project $project, User $user): RedirectResponse
    {
        $this->authorize('update', $project);
        $this->service->removeMember($project, $user);

        return back()->with('success', 'Project member removed.');
    }
}
