{{-- resources/views/questions/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Question')

@section('content')
<div class="fade-in max-w-2xl mx-auto">
    <a href="{{ route('questions.index') }}" class="text-sm font-medium flex items-center gap-1 mb-6" style="color:var(--muted)">← Retour</a>

    <div class="card p-8">
        <div class="flex items-start justify-between gap-4 mb-6">
            <h1 class="text-xl font-bold leading-snug">{{ $question->text }}</h1>
            <span class="badge badge-{{ $question->difficulty }} shrink-0">{{ ucfirst($question->difficulty) }}</span>
        </div>

        <div class="space-y-3">
            @foreach ($question->options as $option)
                <div class="flex items-center gap-3 p-4 rounded-xl border"
                     style="border-color: {{ $option->is_correct ? 'var(--success)' : 'var(--border)' }};
                            background: {{ $option->is_correct ? '#F0FDF4' : '#FAFAFA' }}">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                          style="background: {{ $option->is_correct ? 'var(--success)' : 'var(--border)' }}; color: {{ $option->is_correct ? 'white' : 'var(--muted)' }}">
                        {{ $option->is_correct ? '✓' : '✗' }}
                    </span>
                    <span class="text-sm font-medium">{{ $option->text }}</span>
                </div>
            @endforeach
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t" style="border-color:var(--border)">
            <a href="{{ route('questions.edit', $question) }}" class="btn-primary">Modifier</a>
            <a href="{{ route('questions.index') }}"
               class="px-4 py-2 rounded-xl text-sm font-medium border transition-colors hover:bg-gray-50"
               style="border-color:var(--border); color:var(--muted)">← Retour</a>
        </div>
    </div>
</div>
@endsection