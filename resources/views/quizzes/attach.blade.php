{{-- resources/views/quizzes/attach.blade.php --}}
@extends('layouts.app')
@section('title', 'Gérer les questions')

@section('content')
<div class="fade-in max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('quizzes.show', $quiz) }}" class="text-sm font-medium flex items-center gap-1 mb-4" style="color:var(--muted)">← Retour au quiz</a>
       <!--<h1 class="text-3xl font-bold">Gérer les questions</h1>-->
        <p class="text-sm mt-1" style="color:var(--muted)">Quiz : {{ $quiz->title }}</p>
        @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
            <a href="{{ route('quizzes.attach', $quiz) }}"
       class="text-sm bg-gray-100 px-3 py-1 rounded hover:bg-gray-200">
        ⚙️ Gérer les questions
         @endif
    </a>
       
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('quizzes.attach.store', $quiz) }}" class="space-y-3">
            @csrf

            @forelse($questions as $question)
                <div class="flex items-center gap-4 p-4 rounded-xl border transition-all cursor-pointer hover:border-indigo-300 hover:bg-indigo-50"
                     style="border-color:{{ in_array($question->id, $attachedIds) ? 'var(--primary)' : 'var(--border)' }};
                            background:{{ in_array($question->id, $attachedIds) ? '#EEF2FF' : 'white' }}">

                    <input type="checkbox"
                           name="question_ids[]"
                           value="{{ $question->id }}"
                           id="q_{{ $question->id }}"
                           {{ in_array($question->id, $attachedIds) ? 'checked' : '' }}
                           class="w-4 h-4 rounded shrink-0"
                           style="accent-color:var(--primary)">

                    <label for="q_{{ $question->id }}" class="flex-1 cursor-pointer">
                        <p class="text-sm font-medium">{{ $question->text }}</p>
                        <span class="badge badge-{{ $question->difficulty }} mt-1">{{ ucfirst($question->difficulty) }}</span>
                    </label>

                    <div class="flex items-center gap-2 shrink-0">
                        <label class="text-xs font-medium" style="color:var(--muted)">Points</label>
                        <input type="number"
                               name="points[{{ $question->id }}]"
                               value="{{ $quiz->questions->find($question->id)?->pivot->points ?? 1 }}"
                               min="1" max="10"
                               class="w-16 border rounded-lg px-2 py-1.5 text-sm text-center mono outline-none"
                               style="border-color:var(--border)">
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-4xl mb-2">❓</p>
                    <p class="font-medium">Aucune question disponible</p>
                    <a href="{{ route('questions.create') }}" class="btn-primary mt-4 inline-flex">Créer une question</a>
                </div>
            @endforelse

            @if($questions->count() > 0)
                <div class="pt-4 border-t" style="border-color:var(--border)">
                    <button type="submit" class="btn-primary w-full justify-center py-3">
                        ✓ Sauvegarder les questions
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection