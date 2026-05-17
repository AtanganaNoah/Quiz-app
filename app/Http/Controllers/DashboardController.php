<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'quizzesCount'  => $user->quizzes()->count(),
            'questionsCount' => $user->questions()->count(),
            'attemptsCount' => $user->attempts()->count(),
            'latestQuizzes' => \App\Models\Quiz::where('is_published', true)
                                    ->latest()->take(5)->get(),
        ]);
    }
}