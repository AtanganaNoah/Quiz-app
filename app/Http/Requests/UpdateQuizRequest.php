<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        $quiz = $this->route('quiz');

        // Admin OU le teacher propriétaire du quiz
        return $this->user()?->isAdmin()
            || $this->user()?->id === $quiz?->user_id;
    }

    public function rules(): array
    {
        return [
            'title'        => ['sometimes', 'string', 'min:3', 'max:255'],
            'description'  => ['sometimes', 'nullable', 'string', 'max:2000'],
            'time_limit'   => ['sometimes', 'nullable', 'integer', 'min:1', 'max:300'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'       => 'Le titre doit contenir au moins 3 caractères.',
            'time_limit.min'  => 'La durée minimale est 1 minute.',
            'time_limit.max'  => 'La durée maximale est 300 minutes.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_published')) {
            $this->merge([
                'is_published' => filter_var($this->is_published, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
