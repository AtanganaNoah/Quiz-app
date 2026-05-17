<?php
// app/Http/Controllers/QuestionController.php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::with('options')
            ->latest()
            ->paginate(15);

        return view('questions.index', compact('questions'));
    }

    public function create(): View
    {
        return view('questions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'text'                 => 'required|string',
            'difficulty'           => 'required|in:easy,medium,hard',
            'options'              => 'required|array|min:2',
            'options.*.text'       => 'required|string',
            'options.*.is_correct' => 'required|boolean',
            'options.*.order'      => 'integer|min:0',
        ]);

        $question = $request->user()->questions()->create([
            'text'       => $validated['text'],
            'difficulty' => $validated['difficulty'],
        ]);

        foreach ($validated['options'] as $index => $option) {
            $question->options()->create([
                'text'       => $option['text'],
                'is_correct' => $option['is_correct'],
                'order'      => $option['order'] ?? $index,
            ]);
        }

        return redirect()->route('questions.index')
            ->with('success', 'Question créée.');
    }

    public function show(Question $question): View
    {
        $question->load('options');

        return view('questions.show', compact('question'));
    }

    public function edit(Question $question): View
    {
        $question->load('options');

        return view('questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'text'                 => 'required|string',
            'difficulty'           => 'required|in:easy,medium,hard',
            'options'              => 'required|array|min:2',
            'options.*.text'       => 'required|string',
            'options.*.is_correct' => 'required|boolean',
            'options.*.order'      => 'integer|min:0',
        ]);

        $question->update([
            'text'       => $validated['text'],
            'difficulty' => $validated['difficulty'],
        ]);

        $question->options()->delete();
        foreach ($validated['options'] as $index => $option) {
            $question->options()->create([
                'text'       => $option['text'],
                'is_correct' => $option['is_correct'],
                'order'      => $option['order'] ?? $index,
            ]);
        }

        return redirect()->route('questions.show', $question)
            ->with('success', 'Question mise à jour.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('questions.index')
            ->with('success', 'Question supprimée.');
    }
}