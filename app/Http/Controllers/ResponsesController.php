<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Response as QuizResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ResponsesController extends Controller
{
    /**
     * Enregistre ou met à jour la réponse d'un étudiant
     * pour une question dans une tentative en cours.
     */
    public function store(Request $request, Attempt $attempt): RedirectResponse
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id'   => 'required|exists:options,id',
        ]);

        // Vérifie que la tentative appartient à l'utilisateur connecté
        abort_unless($attempt->user_id === $request->user()->id, 403);
        abort_unless($attempt->status === 'in_progress', 403, 'Tentative déjà terminée.');

        // updateOrCreate : une seule réponse par question par tentative
        QuizResponse::updateOrCreate(
            [
                'attempt_id'  => $attempt->id,
                'question_id' => $validated['question_id'],
            ],
            [
                'option_id' => $validated['option_id'],
                // is_correct est auto-calculé dans le boot() du model Response
            ]
        );

        return back();
    }
}