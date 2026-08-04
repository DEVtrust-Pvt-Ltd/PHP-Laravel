<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository
{
    public function forVisibleProjects(Builder $projects): Builder
    {
        return Task::query()->whereIn('project_id', (clone $projects)->select('id'));
    }

    public function paginateForProject(Project $project, array $filters, int $perPage = 30): LengthAwarePaginator
    {
        return $project->tasks()->with('assignee')
            ->when($filters['status'] ?? null, fn ($query, $value) => $query->where('status', $value))
            ->when($filters['priority'] ?? null, fn ($query, $value) => $query->where('priority', $value))
            ->when($filters['assignee_id'] ?? null, fn ($query, $value) => $query->where('assignee_id', $value))
            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where('title', 'like', '%'.$value.'%'))
            ->orderBy('status')->orderBy('position')->paginate($perPage);
    }

    public function loadAssignee(Task $task): Task
    {
        return $task->load('assignee');
    }

    public function paginateActivities(Task $task, int $perPage = 30): LengthAwarePaginator
    {
        return $task->activities()->with('actor')->latest()->paginate($perPage);
    }

    public function nextPosition(Project $project, string $status): int
    {
        return ((int) $project->tasks()->where('status', $status)->max('position')) + 1;
    }

    public function createFor(Project $project, array $attributes): Task
    {
        return $project->tasks()->create($attributes);
    }

    public function update(Task $task, array $attributes): Task
    {
        $task->update($attributes);

        return $task;
    }

    public function createActivity(Task $task, array $attributes): TaskActivity
    {
        return $task->activities()->create($attributes);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
