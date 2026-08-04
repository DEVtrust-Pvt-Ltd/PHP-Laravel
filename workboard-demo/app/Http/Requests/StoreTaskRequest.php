<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $this->user() !== null && $project !== null && (
            $project->owner_id === $this->user()->id
            || $project->members()->whereKey($this->user())->wherePivot('role', 'member')->exists()
        );
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['sometimes', Rule::in(Task::STATUSES)],
            'priority' => ['sometimes', Rule::in(Task::PRIORITIES)],
            'assignee_id' => [
                'nullable',
                'integer',
                Rule::exists('project_members', 'user_id')
                    ->where('project_id', $this->route('project')?->id),
            ],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
