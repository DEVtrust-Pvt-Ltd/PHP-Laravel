<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\ProjectMemberController;
use App\Http\Controllers\Api\V1\TaskActivityController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\TaskMoveController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('api.v1.auth.login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
        Route::get('me', [AuthController::class, 'me'])->name('api.v1.auth.me');
        Route::get('dashboard', DashboardController::class)->name('api.v1.dashboard');
        Route::apiResource('projects', ProjectController::class)->names('api.v1.projects');
        Route::get('projects/{project}/members', [ProjectMemberController::class, 'index'])->name('api.v1.projects.members.index');
        Route::post('projects/{project}/members', [ProjectMemberController::class, 'store'])->name('api.v1.projects.members.store');
        Route::delete('projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])->name('api.v1.projects.members.destroy');
        Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('api.v1.projects.tasks.index');
        Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('api.v1.projects.tasks.store');
        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('api.v1.tasks.show');
        Route::patch('tasks/{task}', [TaskController::class, 'update'])->name('api.v1.tasks.update');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('api.v1.tasks.destroy');
        Route::post('tasks/{task}/move', TaskMoveController::class)->name('api.v1.tasks.move');
        Route::get('tasks/{task}/activity', TaskActivityController::class)->name('api.v1.tasks.activity.index');
    });
});
