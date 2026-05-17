<?php
// app/Http/Controllers/AttemptController.php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;                  


class AttemptController extends Controller
{
    public function index(Request $request): View
    {
        $attempts = $request->user()
            ->attempts()
            ->with('quiz')
            ->whereHas('quiz')
            ->latest()
            ->paginate(10);

        return view('attempts.index', compact('attempts'));
    }

    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        $existing = $request->user()->attempts()
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            return redirect()->route('attempts.show', $existing);
        }

        $attempt = $request->user()->attempts()->create([
            'quiz_id'    => $quiz->id,
            'score'      => 0,
            'max_score'  => $quiz->maxScore(),
            'status'     => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('attempts.show', $attempt);
    }

    public function show(Attempt $attempt): View
    {
        $attempt->load([
            'quiz.questions.options',
            'responses',
        ]);

        return view('attempts.show', compact('attempt'));
    }

   public function finish(Request $request, Attempt $attempt): RedirectResponse
{
    if ($attempt->status !== 'in_progress') {
        return redirect()->route('attempts.result', $attempt);
    }

    // Sauvegarde toutes les réponses du formulaire
    if ($request->has('answers')) {
        foreach ($request->answers as $questionId => $optionId) {
            \App\Models\Response::updateOrCreate(
                [
                    'attempt_id'  => $attempt->id,
                    'question_id' => $questionId,
                ],
                [
                    'option_id' => $optionId,
                ]
            );
        }
    }

    $attempt->calculateScore();

    return redirect()->route('attempts.result', $attempt);
}

    public function result(Attempt $attempt): View
    {
        $attempt->load([
            'quiz',
            'responses.question.options',
            'responses.option',
        ]);

        return view('attempts.result', compact('attempt'));
    }
}