<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_only_counts_projects_visible_to_the_user(): void
    {
        $user = User::factory()->create();
        $ownProject = Project::factory()->for($user, 'owner')->create();
        Task::factory()->for($ownProject)->create(['status' => 'todo']);

        $otherProject = Project::factory()->create();
        Task::factory()->for($otherProject)->create(['status' => 'done']);

        $this->actingAs($user, 'sanctum')->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('data.projects', 1)
            ->assertJsonPath('data.tasks.total', 1)
            ->assertJsonPath('data.tasks.todo', 1)
            ->assertJsonPath('data.tasks.done', 0);
    }
}
