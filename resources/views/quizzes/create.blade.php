{{-- resources/views/quizzes/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Créer un quiz')

@section('content')
<div class="fade-in max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('quizzes.index') }}" class="text-sm font-medium flex items-center gap-1 mb-4" style="color:var(--muted)">← Retour</a>
        <h1 class="text-3xl font-bold">Créer un quiz</h1>
    </div>

    <div class="card p-8">
        <form method="POST" action="{{ route('quizzes.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Titre <span style="color:var(--danger)">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Ex: Quiz de mathématiques"
                       class="w-full border rounded-xl px-4 py-3 text-sm transition-all outline-none focus:ring-2"
                       style="border-color:var(--border); focus:ring-color:var(--primary)">
                @error('title') <p class="text-xs mt-1" style="color:var(--danger)">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" placeholder="Décrivez votre quiz..."
                          class="w-full border rounded-xl px-4 py-3 text-sm transition-all outline-none focus:ring-2 resize-none"
                          style="border-color:var(--border)">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Limite de temps (minutes)</label>
                <input type="number" name="time_limit" value="{{ old('time_limit') }}" min="1" placeholder="Laisser vide = sans limite"
                       class="w-full border rounded-xl px-4 py-3 text-sm outline-none focus:ring-2"
                       style="border-color:var(--border)">
            </div>

            <div class="flex items-center gap-3 p-4 rounded-xl" style="background:#F8FAFC; border:1px solid var(--border)">
                <input type="checkbox" name="is_published" value="1" id="is_published"
                       {{ old('is_published') ? 'checked' : '' }}
                       class="w-4 h-4 rounded" style="accent-color:var(--primary)">
                <div>
                    <label for="is_published" class="text-sm font-semibold cursor-pointer">Publier immédiatement</label>
                    <p class="text-xs mt-0.5" style="color:var(--muted)">Les étudiants pourront accéder à ce quiz</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 justify-center py-3">Créer le quiz</button>
                <a href="{{ route('quizzes.index') }}"
                   class="flex-1 text-center py-3 rounded-xl text-sm font-medium border transition-colors hover:bg-gray-50"
                   style="border-color:var(--border); color:var(--muted)">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection