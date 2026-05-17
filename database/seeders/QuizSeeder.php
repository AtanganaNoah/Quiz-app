<?php
// database/seeders/QuizSeeder.php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $questions = Question::all();

        // ── Quiz 1 : Culture générale ─────────────────────────────
        $quiz1 = Quiz::create([
            'title'        => 'Culture Générale',
            'description'  => 'Questions de culture générale pour tester vos connaissances.',
            'time_limit'   => 10,
            'is_published' => true,
            'user_id'      => $teacher->id,
        ]);

        // Attache les 2 premières questions (points = 1 par défaut)
        $quiz1->questions()->attach([
            $questions[0]->id => ['points' => 1, 'order' => 1],
            $questions[1]->id => ['points' => 1, 'order' => 2],
            $questions[2]->id => ['points' => 2, 'order' => 3],
        ]);

        // ── Quiz 2 : Développement Web ────────────────────────────
        $quiz2 = Quiz::create([
            'title'        => 'Développement Web',
            'description'  => 'Quiz sur les bases du développement web et Laravel.',
            'time_limit'   => 15,
            'is_published' => true,
            'user_id'      => $teacher->id,
        ]);

        $quiz2->questions()->attach([
            $questions[3]->id => ['points' => 1, 'order' => 1],
            $questions[4]->id => ['points' => 2, 'order' => 2],
            $questions[5]->id => ['points' => 2, 'order' => 3],
            $questions[6]->id => ['points' => 3, 'order' => 4],
            $questions[7]->id => ['points' => 3, 'order' => 5],
        ]);

        // ── Quiz 3 : Non publié (brouillon) ───────────────────────
        $quiz3 = Quiz::create([
            'title'        => 'Quiz Brouillon',
            'description'  => 'Ce quiz n\'est pas encore publié.',
            'time_limit'   => null,
            'is_published' => false,
            'user_id'      => $teacher->id,
        ]);

        $quiz3->questions()->attach([
            $questions[0]->id => ['points' => 1, 'order' => 1],
            $questions[5]->id => ['points' => 2, 'order' => 2],
        ]);
    }
}