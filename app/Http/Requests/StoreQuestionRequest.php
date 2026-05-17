<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isTeacher();
    }

    public function rules(): array
    {
        return [
            'text'        => ['required', 'string', 'min:5', 'max:1000'],
            'difficulty'  => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'category' => ['nullable', 'string', 'max:100'],

            // Options : minimum 2, maximum 6
            'options'              => ['required', 'array', 'min:2', 'max:6'],
            'options.*.text'       => ['required', 'string', 'min:1', 'max:500'],
            'options.*.is_correct' => ['required', 'boolean'],
            'options.*.order'      => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required'              => 'Le texte de la question est obligatoire.',
            'text.min'                   => 'La question doit contenir au moins 5 caractères.',
            'difficulty.required'        => 'La difficulté est obligatoire.',
            'difficulty.in'              => 'La difficulté doit être easy, medium ou hard.',
            'options.required'           => 'Les options de réponse sont obligatoires.',
            'options.min'                => 'La question doit avoir au moins 2 options.',
            'options.max'                => 'La question ne peut pas avoir plus de 6 options.',
            'options.*.text.required'    => 'Le texte de chaque option est obligatoire.',
            'options.*.is_correct.required' => 'Indiquez si chaque option est correcte ou non.',
        ];
    }

    /**
     * Validation après les règles de base :
     * vérifie qu'il y a au moins 1 option correcte.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $options = $this->input('options', []);

            $correctCount = collect($options)->filter(
                fn($opt) => filter_var($opt['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN)
            )->count();

            if ($correctCount === 0) {
                $v->errors()->add('options', 'La question doit avoir au moins une option correcte.');
            }
        });
    }
}
