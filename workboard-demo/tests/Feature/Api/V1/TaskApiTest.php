<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_owner_can_create_move_and_audit_a_task(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->for($owner, 'owner')->create();

        $created = $this->actingAs($owner, 'sanctum')->postJson("/api/v1/projects/{$project->id}/tasks", [
            'title' => 'Ship the first milestone',
            'priority' => 'high',
            'due_date' => today()->addDays(2)->toDateString(),
        ])->assertCreated()->assertJsonPath('data.status', 'todo');

        $task = Task::findOrFail($created->json('data.id'));

        $this->postJson("/api/v1/tasks/{$task->id}/move", ['status' => 'in_progress'])
            ->assertOk()
            ->assertJsonPath('data.status', 'in_progress');

        $this->getJson("/api/v1/tasks/{$task->id}/activity")
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.action', 'moved');
    }

    public function test_viewers_can_read_but_cannot_change_tasks(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();
        $project = Project::factory()->for($owner, 'owner')->create();
        $project->members()->attach($viewer, ['role' => 'viewer']);
        $task = Task::factory()->for($project)->create();

        $this->actingAs($viewer, 'sanctum')->getJson("/api/v1/projects/{$project->id}/tasks")
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->patchJson("/api/v1/tasks/{$task->id}", ['title' => 'Not allowed'])
            ->assertForbidden();
    }
}
