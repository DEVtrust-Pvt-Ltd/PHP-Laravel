<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $service) {}

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $this->service->create($project, $request->validated(), $request->user());

        return back()->with('success', 'Task created.');
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->service->update($task, $request->validated(), $request->user());

        return back()->with('success', 'Task updated.');
    }

    public function move(MoveTaskRequest $request, Task $task): RedirectResponse
    {
        $this->service->move($task, $request->validated(), $request->user());

        return back()->with('success', 'Task moved.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $this->service->delete($task);

        return back()->with('success', 'Task deleted.');
    }
}
