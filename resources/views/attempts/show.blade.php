{{-- resources/views/attempts/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Quiz en cours')

@section('content')
<div class="fade-in">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $attempt->quiz->title }}</h1>
            <p class="text-sm mt-1" style="color:var(--muted)">{{ $attempt->quiz->questions->count() }} question(s)</p>
        </div>
        @if ($attempt->quiz->time_limit)
            <div id="timer" class="mono text-2xl font-bold px-4 py-2 rounded-xl"
                 style="background:#FEE2E2; color:var(--danger)">--:--</div>
        @endif
    </div>

    <form method="POST" action="{{ route('attempts.finish', $attempt) }}" id="quiz-form">
        @csrf

        <div class="space-y-6">
            @foreach ($attempt->quiz->questions as $index => $question)
                @php $response = $attempt->responses->firstWhere('question_id', $question->id); @endphp

                <div class="card p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold mono shrink-0"
                              style="background:#EEF2FF; color:var(--primary)">{{ $index + 1 }}</span>
                        <p class="font-semibold leading-snug pt-1">{{ $question->text }}</p>
                    </div>

                    <div class="space-y-2 pl-11">
                        @foreach ($question->options as $option)
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all hover:border-indigo-300 hover:bg-indigo-50"
                                   style="border-color: {{ $response?->option_id === $option->id ? 'var(--primary)' : 'var(--border)' }};
                                          background: {{ $response?->option_id === $option->id ? '#EEF2FF' : 'white' }}">
                                <input type="radio"
                                       name="answers[{{ $question->id }}]"
                                       value="{{ $option->id }}"
                                       {{ $response?->option_id === $option->id ? 'checked' : '' }}
                                       style="accent-color:var(--primary)">
                                <span class="text-sm">{{ $option->text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 sticky bottom-4">
            <button type="submit" onclick="return confirm('Terminer le quiz ?')"
                    class="btn-primary w-full justify-center py-4 text-base rounded-2xl shadow-lg">
                ✅ Terminer et soumettre
            </button>
        </div>
    </form>
</div>

@if ($attempt->quiz->time_limit)
<script>
    const seconds = {{ $attempt->quiz->time_limit * 60 }};
    const started = new Date("{{ $attempt->started_at->toISOString() }}").getTime();
    const end = started + seconds * 1000;
    const el = document.getElementById('timer');

    function tick() {
        const remaining = Math.max(0, Math.floor((end - Date.now()) / 1000));
        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        el.textContent = m + ':' + s;
        if (remaining <= 60) { el.style.background = '#FEE2E2'; }
        if (remaining === 0) document.getElementById('quiz-form').submit();
    }
    setInterval(tick, 1000); tick();
</script>
@endif
@endsection