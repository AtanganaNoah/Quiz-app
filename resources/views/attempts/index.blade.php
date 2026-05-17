{{-- resources/views/attempts/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Mes tentatives')

@section('content')
<div class="fade-in">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Mes tentatives</h1>
        <p class="text-sm mt-1" style="color:var(--muted)">Historique de vos quiz</p>
    </div>

    <div class="space-y-3">
        @forelse ($attempts as $attempt)
            <div class="card p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold mono shrink-0"
                         style="background: {{ $attempt->percentage() >= 50 ? '#D1FAE5' : ($attempt->status === 'in_progress' ? '#FEF3C7' : '#FEE2E2') }};
                                color: {{ $attempt->percentage() >= 50 ? 'var(--success)' : ($attempt->status === 'in_progress' ? 'var(--warn)' : 'var(--danger)') }}">
                        {{ $attempt->status === 'in_progress' ? '…' : $attempt->percentage() . '%' }}
                    </div>
                    <div>
                        <h2 class="font-semibold">{{ $attempt->quiz?->title ?? 'Quiz supprimé' }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="badge badge-{{ $attempt->status }}">{{ ucfirst(str_replace('_', ' ', $attempt->status)) }}</span>
                            @if($attempt->status === 'completed')
                                <span class="text-xs mono" style="color:var(--muted)">{{ $attempt->score }}/{{ $attempt->max_score }} pts</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="shrink-0 pl-16 sm:pl-0">
                    @if ($attempt->status === 'in_progress')
                        <a href="{{ route('attempts.show', $attempt) }}" class="btn-primary">Continuer →</a>
                    @else
                        <a href="{{ route('attempts.result', $attempt) }}"
                           class="text-sm font-medium px-4 py-2 rounded-xl border transition-colors hover:bg-indigo-50"
                           style="color:var(--primary); border-color:var(--primary)">Voir résultats</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="card p-12 text-center">
                <p class="text-4xl mb-3">🎯</p>
                <p class="font-medium">Aucune tentative pour l'instant</p>
                <a href="{{ route('quizzes.index') }}" class="btn-primary mt-4 inline-flex">Parcourir les quiz</a>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $attempts->links() }}</div>
</div>
@endsection