<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_create_and_view_own_projects(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Website refresh',
                'key' => 'WEB-101',
                'description' => 'A focused public demo project.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Website refresh')
            ->assertJsonPath('data.owner.id', $user->id);

        $this->getJson('/api/v1/projects')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.key', 'WEB-101');
    }

    public function test_a_user_cannot_view_or_update_another_users_project(): void
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/projects/{$project->id}")
            ->assertForbidden();

        $this->patchJson("/api/v1/projects/{$project->id}", ['name' => 'Changed'])
            ->assertForbidden();
    }

    public function test_project_key_is_validated_and_unique(): void
    {
        $user = User::factory()->create();
        Project::factory()->for($user, 'owner')->create(['key' => 'WEB-101']);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/projects', ['name' => '', 'key' => 'web key'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'key']);

        $this->postJson('/api/v1/projects', ['name' => 'New project', 'key' => 'WEB-101'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['key']);
    }

    public function test_project_key_is_normalized_before_validation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Devtrust',
                'key' => 'Web-101',
            ])
            ->assertCreated()
            ->assertJsonPath('data.key', 'WEB-101');

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Devtrust',
            'key' => 'WEB-101',
        ]);
    }
}
