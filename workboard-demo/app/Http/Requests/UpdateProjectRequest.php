<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('key')) {
            $this->merge([
                'key' => str($this->string('key')->value())->upper()->value(),
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('project')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'key' => ['sometimes', 'required', 'string', 'max:12', 'regex:/^[A-Z][A-Z0-9-]*$/', 'unique:projects,key,'.($this->route('project') instanceof Project ? $this->route('project')->id : '')],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ];
    }
}
