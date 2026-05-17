<?php
// app/Http/Controllers/QuizController.php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(): View
    {
        $quizzes = Quiz::with('user')
            ->withCount('questions')
            ->latest()
            ->paginate(10);

        return view('quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        return view('quizzes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'time_limit'   => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        $quiz = $request->user()->quizzes()->create($validated);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz créé avec succès.');
    }

    public function show(Quiz $quiz): View
    {
        $quiz->load(['questions.options', 'user']);

        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz): View
    {
        $quiz->load('questions');

        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'time_limit'   => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        $quiz->update($validated);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz mis à jour.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz supprimé.');
    }


    public function attachQuestions(Quiz $quiz): View
{
    $questions = Question::with('options')->get();
    $attachedIds = $quiz->questions->pluck('id')->toArray();

    return view('quizzes.attach', compact('quiz', 'questions', 'attachedIds'));
}

public function storeAttach(Request $request, Quiz $quiz): RedirectResponse
{
    $questionIds = $request->input('question_ids', []);
    $points      = $request->input('points', []);

    $sync = [];
    foreach ($questionIds as $index => $id) {
        $sync[$id] = [
            'points' => $points[$id] ?? 1,
            'order'  => $index + 1,
        ];
    }

    $quiz->questions()->sync($sync);

    return redirect()->route('quizzes.show', $quiz)
        ->with('success', 'Questions attachées avec succès.');
}
}