{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Bonjour, {{ auth()->user()->name }} 👋</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded shadow p-5 text-center">
        <p class="text-3xl font-bold text-indigo-600">{{ $quizzesCount }}</p>
        <p class="text-gray-500 mt-1">Quiz créés</p>
    </div>
    <div class="bg-white rounded shadow p-5 text-center">
        <p class="text-3xl font-bold text-indigo-600">{{ $questionsCount }}</p>
        <p class="text-gray-500 mt-1">Questions</p>
    </div>
    <div class="bg-white rounded shadow p-5 text-center">
        <p class="text-3xl font-bold text-indigo-600">{{ $attemptsCount }}</p>
        <p class="text-gray-500 mt-1">Tentatives</p>
    </div>
</div>

<div class="bg-white rounded shadow p-5">
    <h2 class="font-semibold text-lg mb-4">Derniers quiz disponibles</h2>
    @forelse ($latestQuizzes as $quiz)
        <div class="flex justify-between items-center py-2 border-b last:border-0">
            <span>{{ $quiz->title }}</span>
            <a href="{{ route('quizzes.show', $quiz) }}"
               class="text-sm text-indigo-600 hover:underline">Voir</a>
        </div>
    @empty
        <p class="text-gray-400">Aucun quiz disponible.</p>
    @endforelse
</div>
@endsection