{{-- resources/views/questions/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Nouvelle question')

@section('content')
<div class="fade-in max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('questions.index') }}" class="text-sm font-medium flex items-center gap-1 mb-4" style="color:var(--muted)">← Retour</a>
        <h1 class="text-3xl font-bold">Nouvelle question</h1>
    </div>

    <div class="card p-8">
        <form method="POST" action="{{ route('questions.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Énoncé <span style="color:var(--danger)">*</span></label>
                <textarea name="text" rows="3" required placeholder="Quelle est votre question ?"
                          class="w-full border rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 resize-none"
                          style="border-color:var(--border)">{{ old('text') }}</textarea>
                @error('text') <p class="text-xs mt-1" style="color:var(--danger)">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Difficulté</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['easy' => ['label' => 'Facile', 'color' => 'var(--success)'], 'medium' => ['label' => 'Moyen', 'color' => 'var(--warn)'], 'hard' => ['label' => 'Difficile', 'color' => 'var(--danger)']] as $val => $opt)
                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all hover:border-gray-400"
                               style="border-color:var(--border)">
                            <input type="radio" name="difficulty" value="{{ $val }}"
                                   {{ old('difficulty', 'easy') === $val ? 'checked' : '' }}
                                   style="accent-color:{{ $opt['color'] }}">
                            <span class="text-sm font-medium">{{ $opt['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-3">Options de réponse <span style="color:var(--danger)">*</span></label>
                <div class="space-y-3">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="flex items-center gap-3 p-3 rounded-xl border" style="border-color:var(--border)">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                  style="background:#EEF2FF; color:var(--primary)">{{ chr(65 + $i) }}</span>
                            <input type="text" name="options[{{ $i }}][text]"
                                   placeholder="Option {{ chr(65 + $i) }}"
                                   value="{{ old("options.$i.text") }}"
                                   class="flex-1 outline-none text-sm bg-transparent"
                                   required>
                            <input type="hidden" name="options[{{ $i }}][is_correct]" value="0">
                            <label class="flex items-center gap-1.5 text-xs font-medium shrink-0 cursor-pointer" style="color:var(--success)">
                                <input type="checkbox" name="options[{{ $i }}][is_correct]" value="1"
                                       {{ old("options.$i.is_correct") ? 'checked' : '' }}
                                       style="accent-color:var(--success)">
                                Correcte
                            </label>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 justify-center py-3">Enregistrer</button>
                <a href="{{ route('questions.index') }}"
                   class="flex-1 text-center py-3 rounded-xl text-sm font-medium border transition-colors hover:bg-gray-50"
                   style="border-color:var(--border); color:var(--muted)">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection