<?php

namespace App\Http\Requests;

use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAttemptRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Tout utilisateur connecté peut démarrer une tentative
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'quiz_id.required' => 'Le quiz est obligatoire.',
            'quiz_id.exists'   => 'Ce quiz n\'existe pas.',
        ];
    }

    /**
     * Vérifie que :
     * 1. Le quiz est publié
     * 2. L'utilisateur n'a pas déjà une tentative en cours sur ce quiz
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $quiz = Quiz::find($this->quiz_id);

            if ($quiz && ! $quiz->is_published) {
                $v->errors()->add('quiz_id', 'Ce quiz n\'est pas encore disponible.');
            }

            $hasInProgress = $this->user()
                ->attempts()
                ->where('quiz_id', $this->quiz_id)
                ->where('status', 'in_progress')
                ->exists();

            if ($hasInProgress) {
                $v->errors()->add('quiz_id', 'Vous avez déjà une tentative en cours sur ce quiz.');
            }
        });
    }
}
