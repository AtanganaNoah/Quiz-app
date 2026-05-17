{{-- resources/views/quizzes/show.blade.php --}}
@extends('layouts.app')
@section('title', $quiz->title)

@section('content')
<div class="fade-in">
    <a href="{{ route('quizzes.index') }}" class="text-sm font-medium flex items-center gap-1 mb-6" style="color:var(--muted)">← Tous les quiz</a>

    <div class="card p-8 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">{{ $quiz->title }}</h1>
                @if($quiz->description)
                    <p class="mt-2 text-sm" style="color:var(--muted)">{{ $quiz->description }}</p>
                @endif
                <div class="flex flex-wrap gap-3 mt-4">
                    <span class="badge" style="background:#EEF2FF; color:var(--primary)">
                        📋 {{ $quiz->questions->count() }} question(s)
                    </span>
                    <span class="badge" style="background:#F0FDFA; color:#0F766E">
                        ⏱ {{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'Sans limite' }}
                    </span>
                    <span class="badge {{ $quiz->is_published ? 'badge-completed' : 'badge-abandoned' }}">
                        {{ $quiz->is_published ? '✓ Publié' : '✗ Brouillon' }}
                    </span>
                </div>
            </div>

            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('quizzes.attach', $quiz) }}"
                       class="text-sm font-medium px-4 py-2 rounded-xl border transition-colors hover:bg-gray-50"
                       style="border-color:var(--border); color:var(--muted)">⚙️ Questions</a>
                    <a href="{{ route('quizzes.edit', $quiz) }}"
                       class="text-sm font-medium px-4 py-2 rounded-xl border transition-colors hover:bg-yellow-50"
                       style="border-color:var(--border); color:var(--warn)">Modifier</a>
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('attempts.store', $quiz) }}" class="mt-6">
            @csrf
            <button type="submit" class="btn-primary py-3 px-8 text-base">
                🚀 Démarrer le quiz
            </button>
        </form>
    </div>
@if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
    @if($quiz->questions->count() > 0)
        <h2 class="font-semibold text-lg mb-4">Questions du quiz</h2>
        <div class="space-y-3">
            @foreach ($quiz->questions as $index => $question)
                <div class="card p-4 flex items-center gap-4">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold mono shrink-0"
                          style="background:#EEF2FF; color:var(--primary)">{{ $index + 1 }}</span>
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ $question->text }}</p>
                    </div>
                    <span class="badge badge-{{ $question->difficulty }}">{{ ucfirst($question->difficulty) }}</span>
                </div>
            @endforeach
        </div>
    @endif
@endif    
</div>
@endsection