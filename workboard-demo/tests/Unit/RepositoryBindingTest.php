<?php

namespace Tests\Unit;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Services\DashboardService;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Services\UserService;
use Tests\TestCase;

class RepositoryBindingTest extends TestCase
{
    public function test_repositories_resolve_from_the_container(): void
    {
        $this->assertInstanceOf(ProjectRepository::class, app(ProjectRepository::class));
        $this->assertInstanceOf(TaskRepository::class, app(TaskRepository::class));
        $this->assertInstanceOf(UserRepository::class, app(UserRepository::class));
    }

    public function test_application_services_resolve_with_their_dependencies(): void
    {
        $this->assertInstanceOf(ProjectService::class, app(ProjectService::class));
        $this->assertInstanceOf(TaskService::class, app(TaskService::class));
        $this->assertInstanceOf(DashboardService::class, app(DashboardService::class));
        $this->assertInstanceOf(UserService::class, app(UserService::class));
    }
}
