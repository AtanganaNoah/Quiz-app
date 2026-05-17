{{-- resources/views/questions/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Modifier la question')

@section('content')
<h1 class="text-2xl font-bold mb-6">Modifier la question</h1>

<form method="POST" action="{{ route('questions.update', $question) }}"
      class="bg-white rounded shadow p-6 space-y-5">
    @csrf @method('PUT')

    <div>
        <label class="block text-sm font-medium text-gray-700">Énoncé</label>
        <textarea name="text" rows="3" required
                  class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
            {{ old('text', $question->text) }}
        </textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Difficulté</label>
        <select name="difficulty"
                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
            @foreach(['easy' => 'Facile', 'medium' => 'Moyen', 'hard' => 'Difficile'] as $val => $label)
                <option value="{{ $val }}"
                    {{ old('difficulty', $question->difficulty) === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="space-y-3">
        <label class="block text-sm font-medium text-gray-700">Options de réponse</label>
        @foreach ($question->options as $i => $option)
            <div class="flex items-center gap-3">
                <input type="text" name="options[{{ $i }}][text]"
                       value="{{ old("options.$i.text", $option->text) }}"
                       class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300"
                       required>
                <input type="hidden" name="options[{{ $i }}][is_correct]" value="0">
                <label class="flex items-center gap-1 text-sm text-gray-600">
                    <input type="checkbox" name="options[{{ $i }}][is_correct]" value="1"
                           {{ old("options.$i.is_correct", $option->is_correct) ? 'checked' : '' }}>
                    Correcte
                </label>
            </div>
        @endforeach
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit"
                class="bg-yellow-500 text-white px-5 py-2 rounded hover:bg-yellow-600">
            Mettre à jour
        </button>
        <a href="{{ route('questions.show', $question) }}" class="text-gray-500 py-2 hover:underline">Annuler</a>
    </div>
</form>
@endsection