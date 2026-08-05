<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ProjectRepository
{
    public function visibleTo(User $user): Builder
    {
        return Project::query()->visibleTo($user);
    }

    public function loadBoard(Project $project): Project
    {
        return $project->load(['owner', 'members', 'tasks.assignee', 'tasks.activities.actor']);
    }

    public function loadOwner(Project $project): Project
    {
        return $project->load('owner');
    }

    public function loadMembers(Project $project): Project
    {
        return $project->load(['owner', 'members']);
    }

    public function createFor(User $owner, array $attributes): Project
    {
        return $owner->projects()->create($attributes);
    }

    public function attachMember(Project $project, User $user, string $role): void
    {
        $project->members()->syncWithoutDetaching([$user->id => ['role' => $role]]);
    }

    public function detachMember(Project $project, User $user): void
    {
        $project->members()->detach($user);
    }

    public function unassignTasksFor(Project $project, User $user): void
    {
        $project->tasks()->whereBelongsTo($user, 'assignee')->update(['assignee_id' => null]);
    }

    public function update(Project $project, array $attributes): Project
    {
        $project->update($attributes);

        return $project->refresh();
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
