<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkBoardPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_owner_can_open_the_dashboard_and_project_board(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->for($owner, 'owner')->create();
        Task::factory()->for($project)->create();

        $this->actingAs($owner)->get('/dashboard')->assertOk();
        $this->get("/projects/{$project->id}")->assertOk();
    }
}
