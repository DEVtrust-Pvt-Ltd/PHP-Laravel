<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;

class DashboardService
{
    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly TaskRepository $tasks,
    ) {}

    public function dataFor(User $user): array
    {
        $projects = $this->projects->visibleTo($user);
        $tasks = $this->tasks->forVisibleProjects($projects);

        return [
            'summary' => [
                'projects' => (clone $projects)->count(),
                'todo' => (clone $tasks)->where('status', 'todo')->count(),
                'in_progress' => (clone $tasks)->where('status', 'in_progress')->count(),
                'done' => (clone $tasks)->where('status', 'done')->count(),
                'overdue' => (clone $tasks)->whereNot('status', 'done')->whereDate('due_date', '<', today())->count(),
            ],
            'dueSoon' => (clone $tasks)->with(['assignee', 'project'])->whereNot('status', 'done')
                ->whereBetween('due_date', [today(), today()->addDays(7)])->orderBy('due_date')->limit(6)->get(),
            'recentProjects' => $projects->with('owner')->withCount('tasks')->latest('updated_at')->limit(4)->get()
                ->map(fn (Project $project) => [
                    'id' => $project->id, 'name' => $project->name, 'key' => $project->key,
                    'task_count' => $project->tasks_count, 'updated_at' => $project->updated_at?->toISOString(),
                ]),
        ];
    }
}
