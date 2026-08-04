<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::query()->updateOrCreate(
            ['email' => 'demo@workboard.test'],
            ['name' => 'Demo User', 'password' => Hash::make('password'), 'email_verified_at' => now()],
        );
        $alex = User::query()->updateOrCreate(
            ['email' => 'alex@workboard.test'],
            ['name' => 'Alex Morgan', 'password' => Hash::make('password'), 'email_verified_at' => now()],
        );
        $sam = User::query()->updateOrCreate(
            ['email' => 'sam@workboard.test'],
            ['name' => 'Sam Lee', 'password' => Hash::make('password'), 'email_verified_at' => now()],
        );

        $projects = [
            ['name' => 'Marketing site refresh', 'key' => 'WEB-101', 'description' => 'A modern, accessible launch site for a fictional product team.'],
            ['name' => 'Mobile onboarding', 'key' => 'MOB-220', 'description' => 'Improve first-week activation through clearer onboarding.'],
            ['name' => 'Analytics foundation', 'key' => 'DATA-310', 'description' => 'Create reliable reporting for product and support teams.'],
        ];

        foreach ($projects as $projectData) {
            $project = Project::query()->updateOrCreate(['key' => $projectData['key']], $projectData + ['owner_id' => $demo->id]);
            $project->members()->syncWithoutDetaching([
                $demo->id => ['role' => 'owner'],
                $alex->id => ['role' => 'member'],
                $sam->id => ['role' => 'viewer'],
            ]);

            foreach ([
                ['Define success metrics', 'todo', 'high', $alex, 2],
                ['Review content outline', 'todo', 'medium', $demo, 4],
                ['Build responsive prototype', 'in_progress', 'high', $alex, 1],
                ['Confirm release checklist', 'in_progress', 'medium', $demo, 6],
                ['Create project brief', 'done', 'low', $demo, -2],
                ['Share stakeholder update', 'done', 'medium', $alex, -1],
                ['Run accessibility review', 'todo', 'high', $demo, 5],
            ] as $position => [$title, $status, $priority, $assignee, $days]) {
                $task = Task::query()->updateOrCreate(
                    ['project_id' => $project->id, 'title' => $title],
                    [
                        'description' => 'Demo task for '.$project->name.'.',
                        'status' => $status,
                        'priority' => $priority,
                        'assignee_id' => $assignee->id,
                        'due_date' => today()->addDays($days),
                        'position' => $position + 1,
                    ],
                );
                $task->activities()->firstOrCreate(['action' => 'created'], ['actor_id' => $demo->id]);
            }
        }
    }
}
