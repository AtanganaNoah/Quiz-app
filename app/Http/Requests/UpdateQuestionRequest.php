<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $question = $this->route('question');

        return $this->user()?->isAdmin()
            || $this->user()?->id === $question?->user_id;
    }

    public function rules(): array
    {
        return [
            'text'        => ['sometimes', 'string', 'min:5', 'max:1000'],
            'difficulty'  => ['sometimes', Rule::in(['easy', 'medium', 'hard'])],
            'category' => ['sometimes', 'nullable', 'string', 'max:100'],

            'options'              => ['sometimes', 'array', 'min:2', 'max:6'],
            'options.*.text'       => ['required_with:options', 'string', 'min:1', 'max:500'],
            'options.*.is_correct' => ['required_with:options', 'boolean'],
            'options.*.order'      => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $options = $this->input('options');

            // Vérification uniquement si les options sont envoyées
            if (! is_null($options)) {
                $correctCount = collect($options)->filter(
                    fn($opt) => filter_var($opt['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN)
                )->count();

                if ($correctCount === 0) {
                    $v->errors()->add('options', 'La question doit avoir au moins une option correcte.');
                }
            }
        });
    }
}
