{{-- resources/views/questions/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Banque de questions')

@section('content')
<div class="fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold">Banque de questions</h1>
            <p class="text-sm mt-1" style="color:var(--muted)">{{ $questions->total() }} questions disponibles</p>
        </div>
        <a href="{{ route('questions.create') }}" class="btn-primary">+ Nouvelle question</a>
    </div>

    <div class="space-y-3">
        @forelse ($questions as $question)
            <div class="card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-10 rounded-full shrink-0"
                         style="background: {{ $question->difficulty === 'easy' ? 'var(--success)' : ($question->difficulty === 'medium' ? 'var(--warn)' : 'var(--danger)') }}">
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ $question->text }}</p>
                        <span class="badge badge-{{ $question->difficulty }} mt-1">{{ ucfirst($question->difficulty) }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 pl-5 sm:pl-0">
                    <a href="{{ route('questions.show', $question) }}"
                       class="text-xs font-medium px-3 py-1.5 rounded-lg transition-colors hover:bg-indigo-50"
                       style="color:var(--primary)">Voir</a>
                    <a href="{{ route('questions.edit', $question) }}"
                       class="text-xs font-medium px-3 py-1.5 rounded-lg transition-colors hover:bg-yellow-50"
                       style="color:var(--warn)">Modifier</a>
                    <form method="POST" action="{{ route('questions.destroy', $question) }}">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Supprimer cette question ?')"
                                class="text-xs font-medium px-3 py-1.5 rounded-lg transition-colors hover:bg-red-50"
                                style="color:var(--danger)">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center">
                <p class="text-4xl mb-3">❓</p>
                <p class="font-medium">Aucune question pour l'instant</p>
                <a href="{{ route('questions.create') }}" class="btn-primary mt-4 inline-flex">Créer une question</a>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $questions->links() }}</div>
</div>
@endsection