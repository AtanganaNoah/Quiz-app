<?php

namespace App\Http\Requests;

use App\Models\Attempt;
use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $attempt = $this->route('attempt');

        // L'étudiant ne peut répondre qu'à ses propres tentatives
        return $this->user()?->id === $attempt?->user_id;
    }

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'option_id'   => ['required', 'integer', 'exists:options,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'question_id.required' => 'La question est obligatoire.',
            'question_id.exists'   => 'Cette question n\'existe pas.',
            'option_id.required'   => 'Vous devez choisir une réponse.',
            'option_id.exists'     => 'Cette option n\'existe pas.',
        ];
    }

    /**
     * Vérifie que :
     * 1. La tentative est bien en cours (pas terminée)
     * 2. La question fait partie du quiz de cette tentative
     * 3. L'option appartient bien à la question
     * 4. L'étudiant n'a pas déjà répondu à cette question dans cette tentative
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            /** @var Attempt $attempt */
            $attempt = $this->route('attempt');

            // 1. Tentative en cours ?
            if ($attempt->status !== 'in_progress') {
                $v->errors()->add('attempt', 'Cette tentative est déjà terminée.');
                return; // inutile de continuer
            }

            // 2. La question fait-elle partie du quiz ?
            $questionInQuiz = $attempt->quiz
                ->questions()
                ->where('questions.id', $this->question_id)
                ->exists();

            if (! $questionInQuiz) {
                $v->errors()->add('question_id', 'Cette question ne fait pas partie de ce quiz.');
            }

            // 3. L'option appartient-elle à la question ?
            $optionBelongsToQuestion = Option::where('id', $this->option_id)
                ->where('question_id', $this->question_id)
                ->exists();

            if (! $optionBelongsToQuestion) {
                $v->errors()->add('option_id', 'Cette option ne correspond pas à la question.');
            }

            // 4. Déjà répondu ?
            $alreadyAnswered = $attempt->responses()
                ->where('question_id', $this->question_id)
                ->exists();

            if ($alreadyAnswered) {
                $v->errors()->add('question_id', 'Vous avez déjà répondu à cette question.');
            }
        });
    }
}
