<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isTeacher();
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'min:3', 'max:255'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'time_limit'   => ['nullable', 'integer', 'min:1', 'max:300'],
            'is_published' => ['sometimes', 'boolean'],

            // Questions à attacher au quiz (optionnel à la création)
            'questions'              => ['sometimes', 'array', 'min:1'],
            'questions.*.id'         => ['required_with:questions', 'integer', 'exists:questions,id'],
            'questions.*.points'     => ['sometimes', 'integer', 'min:1', 'max:100'],
            'questions.*.order'      => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'             => 'Le titre du quiz est obligatoire.',
            'title.min'                  => 'Le titre doit contenir au moins 3 caractères.',
            'time_limit.min'             => 'La durée minimale est 1 minute.',
            'time_limit.max'             => 'La durée maximale est 300 minutes (5h).',
            'questions.*.id.exists'      => 'Une des questions sélectionnées n\'existe pas.',
            'questions.*.points.min'     => 'Le nombre de points minimum est 1.',
            'questions.*.points.max'     => 'Le nombre de points maximum est 100.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Cast is_published en booléen si envoyé comme string "true"/"false"
        if ($this->has('is_published')) {
            $this->merge([
                'is_published' => filter_var($this->is_published, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}