<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;        // ← manquant
use App\Http\Controllers\QuestionController;    // ← manquant
use App\Http\Controllers\AttemptController;     // ← manquant
use App\Http\Controllers\ResponseController;    

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::middleware('auth')->group(function () {

    Route::resource('quizzes', QuizController::class);
    Route::resource('questions', QuestionController::class);
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Attacher des questions à un quiz
Route::get('quizzes/{quiz}/attach-questions', [QuizController::class, 'attachQuestions'])->name('quizzes.attach');
Route::post('quizzes/{quiz}/attach-questions', [QuizController::class, 'storeAttach'])->name('quizzes.attach.store');

    // Attempts
    Route::post('quizzes/{quiz}/attempts', [AttemptController::class, 'store'])->name('attempts.store');
    Route::get('attempts', [AttemptController::class, 'index'])->name('attempts.index');
    Route::get('attempts/{attempt}', [AttemptController::class, 'show'])->name('attempts.show');
    Route::post('attempts/{attempt}/finish', [AttemptController::class, 'finish'])->name('attempts.finish');
    Route::get('attempts/{attempt}/result', [AttemptController::class, 'result'])->name('attempts.result');

    // Responses
    Route::post('attempts/{attempt}/responses', [ResponseController::class, 'store'])->name('responses.store');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');