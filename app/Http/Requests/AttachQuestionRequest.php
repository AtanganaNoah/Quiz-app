<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AttachQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $quiz = $this->route('quiz');

        return $this->user()?->isAdmin()
            || $this->user()?->id === $quiz?->user_id;
    }

    public function rules(): array
    {
        return [
            'questions'          => ['required', 'array', 'min:1'],
            'questions.*.id'     => ['required', 'integer', 'exists:questions,id'],
            'questions.*.points' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'questions.*.order'  => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'questions.required'     => 'Sélectionnez au moins une question.',
            'questions.*.id.exists'  => 'Une des questions sélectionnées n\'existe pas.',
            'questions.*.points.min' => 'Le nombre de points minimum est 1.',
        ];
    }

    /**
     * Vérifie qu'on n'attache pas une question déjà présente dans le quiz.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $quiz = $this->route('quiz');
            $incomingIds = collect($this->input('questions', []))->pluck('id');

            $existingIds = $quiz->questions()->pluck('questions.id');

            $duplicates = $incomingIds->intersect($existingIds);

            if ($duplicates->isNotEmpty()) {
                $v->errors()->add(
                    'questions',
                    'Ces questions sont déjà dans le quiz : ' . $duplicates->implode(', ')
                );
            }
        });
    }
}
