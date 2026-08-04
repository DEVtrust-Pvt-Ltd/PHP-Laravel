<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly UserRepository $users,
        private readonly DatabaseManager $database,
    ) {}

    public function listFor(User $user, ?string $search = null): Collection
    {
        return $this->projects->visibleTo($user)
            ->with('owner')->withCount('tasks')
            ->when($search, fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
            ->latest()->get();
    }

    public function paginateFor(User $user, int $perPage = 12): LengthAwarePaginator
    {
        return $this->projects->visibleTo($user)->with('owner')->latest()->paginate($perPage);
    }

    public function withOwner(Project $project): Project
    {
        return $this->projects->loadOwner($project);
    }

    public function withMembers(Project $project): Project
    {
        return $this->projects->loadMembers($project);
    }

    public function create(User $owner, array $attributes): Project
    {
        return $this->database->transaction(function () use ($owner, $attributes): Project {
            $project = $this->projects->createFor($owner, $attributes);
            $this->projects->attachMember($project, $owner, 'owner');

            return $project;
        });
    }

    public function boardData(Project $project, User $user): array
    {
        $project = $this->projects->loadBoard($project);

        return [
            'project' => $project,
            'tasks' => $project->tasks->sortBy('position')->values(),
            'activities' => $project->tasks->flatMap->activities->sortByDesc('created_at')->take(12)->values(),
            'permissions' => [
                'editProject' => $user->can('update', $project),
                'deleteProject' => $user->can('delete', $project),
                'editTasks' => $project->owner_id === $user->id
                    || $project->members()->whereKey($user)->wherePivot('role', 'member')->exists(),
            ],
        ];
    }

    public function update(Project $project, array $attributes): Project
    {
        return $this->projects->update($project, $attributes);
    }

    public function delete(Project $project): void
    {
        $this->projects->delete($project);
    }

    public function addExistingMember(Project $project, string $email, string $role): User
    {
        $member = $this->users->findByEmail($email);
        if (! $member) {
            throw ValidationException::withMessages(['email' => 'No WorkBoard account exists with this email.']);
        }
        if ($member->is($project->owner)) {
            throw ValidationException::withMessages(['email' => 'The project owner already belongs to this project.']);
        }
        $this->projects->attachMember($project, $member, $role);

        return $member;
    }

    public function createMember(Project $project, array $attributes): User
    {
        return $this->database->transaction(function () use ($project, $attributes): User {
            $member = $this->users->create([
                'name' => $attributes['name'], 'email' => $attributes['email'],
                'password' => $attributes['password'], 'must_change_password' => true,
                'email_verified_at' => now(),
            ]);
            $this->projects->attachMember($project, $member, $attributes['role']);

            return $member;
        });
    }

    public function removeMember(Project $project, User $member): void
    {
        if ($member->is($project->owner)) {
            throw ValidationException::withMessages(['member' => 'The project owner cannot be removed.']);
        }
        $this->database->transaction(function () use ($project, $member): void {
            $this->projects->unassignTasksFor($project, $member);
            $this->projects->detachMember($project, $member);
        });
    }
}
