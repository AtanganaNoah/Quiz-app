{{-- resources/views/quizzes/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Quiz')

@section('content')
<div class="fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold">Tous les quiz</h1>
            <p class="text-sm mt-1" style="color:var(--muted)">{{ $quizzes->total() }} quiz disponibles</p>
        </div>
        @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
            <a href="{{ route('quizzes.create') }}" class="btn-primary">+ Nouveau quiz</a>
        @endif
    </div>

    <div class="grid gap-4">
        @forelse ($quizzes as $quiz)
            <div class="card p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl font-bold shrink-0"
                         style="background: #EEF2FF; color: var(--primary)">
                        {{ strtoupper(substr($quiz->title, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="font-semibold">{{ $quiz->title }}</h2>
                        <p class="text-xs mt-1" style="color:var(--muted)">
                            <span class="mono">{{ $quiz->questions_count }}</span> question(s) —
                            {{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'Sans limite' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('quizzes.show', $quiz) }}"
                       class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors hover:bg-indigo-50"
                       style="color:var(--primary)">Voir</a>
                    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                        <a href="{{ route('quizzes.attach', $quiz) }}"
                           class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors hover:bg-gray-50"
                           style="color:var(--muted)">Questions</a>
                        <a href="{{ route('quizzes.edit', $quiz) }}"
                           class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors hover:bg-yellow-50"
                           style="color:var(--warn)">Modifier</a>
                        <form method="POST" action="{{ route('quizzes.destroy', $quiz) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Supprimer ce quiz ?')"
                                    class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors hover:bg-red-50"
                                    style="color:var(--danger)">Supprimer</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="card p-12 text-center">
                <p class="text-4xl mb-3">📋</p>
                <p class="font-medium">Aucun quiz pour l'instant</p>
                <p class="text-sm mt-1" style="color:var(--muted)">Créez votre premier quiz</p>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $quizzes->links() }}</div>
</div>
@endsection