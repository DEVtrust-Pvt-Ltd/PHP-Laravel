<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $tasks,
        private readonly DatabaseManager $database,
    ) {}

    public function create(Project $project, array $attributes, User $actor): Task
    {
        $attributes['status'] ??= 'todo';
        $attributes['priority'] ??= 'medium';
        $attributes['position'] = $this->tasks->nextPosition($project, $attributes['status']);

        return $this->database->transaction(function () use ($project, $attributes, $actor): Task {
            $task = $this->tasks->createFor($project, $attributes);
            $this->tasks->createActivity($task, ['actor_id' => $actor->id, 'action' => 'created']);

            return $task;
        });
    }

    public function paginate(Project $project, array $filters): LengthAwarePaginator
    {
        return $this->tasks->paginateForProject($project, $filters);
    }

    public function withAssignee(Task $task): Task
    {
        return $this->tasks->loadAssignee($task);
    }

    public function activities(Task $task): LengthAwarePaginator
    {
        return $this->tasks->paginateActivities($task);
    }

    public function update(Task $task, array $attributes, User $actor): Task
    {
        return $this->database->transaction(function () use ($task, $attributes, $actor): Task {
            $this->tasks->update($task, $attributes);
            if ($task->wasChanged()) {
                $this->tasks->createActivity($task, [
                    'actor_id' => $actor->id, 'action' => 'updated',
                    'metadata' => ['fields' => array_keys($task->getChanges())],
                ]);
            }

            return $task->refresh();
        });
    }

    public function move(Task $task, array $attributes, User $actor): Task
    {
        return $this->database->transaction(function () use ($task, $attributes, $actor): Task {
            $before = $task->status;
            $this->tasks->update($task, [
                'status' => $attributes['status'],
                'position' => $attributes['position'] ?? $task->position,
            ]);
            $this->tasks->createActivity($task, [
                'actor_id' => $actor->id, 'action' => 'moved',
                'metadata' => ['from' => $before, 'to' => $task->status],
            ]);

            return $task->refresh();
        });
    }

    public function delete(Task $task): void
    {
        $this->tasks->delete($task);
    }
}
