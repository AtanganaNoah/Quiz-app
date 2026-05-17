<?php
// database/seeders/QuestionSeeder.php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first();

        $questions = [
            [
                'text'       => 'Quelle est la capitale de la France ?',
                'difficulty' => 'easy',
                'options'    => [
                    ['text' => 'Paris',    'is_correct' => true],
                    ['text' => 'Lyon',     'is_correct' => false],
                    ['text' => 'Marseille','is_correct' => false],
                    ['text' => 'Bordeaux', 'is_correct' => false],
                ],
            ],
            [
                'text'       => 'Combien vaut PI (2 décimales) ?',
                'difficulty' => 'easy',
                'options'    => [
                    ['text' => '3.14', 'is_correct' => true],
                    ['text' => '3.16', 'is_correct' => false],
                    ['text' => '3.12', 'is_correct' => false],
                    ['text' => '3.18', 'is_correct' => false],
                ],
            ],
            [
                'text'       => 'Quel est le résultat de 12 × 12 ?',
                'difficulty' => 'medium',
                'options'    => [
                    ['text' => '144', 'is_correct' => true],
                    ['text' => '124', 'is_correct' => false],
                    ['text' => '132', 'is_correct' => false],
                    ['text' => '148', 'is_correct' => false],
                ],
            ],
            [
                'text'       => 'Quel langage est utilisé pour styliser les pages web ?',
                'difficulty' => 'easy',
                'options'    => [
                    ['text' => 'CSS',    'is_correct' => true],
                    ['text' => 'PHP',    'is_correct' => false],
                    ['text' => 'Python', 'is_correct' => false],
                    ['text' => 'Java',   'is_correct' => false],
                ],
            ],
            [
                'text'       => 'Que signifie MVC ?',
                'difficulty' => 'medium',
                'options'    => [
                    ['text' => 'Model View Controller',  'is_correct' => true],
                    ['text' => 'Model View Component',   'is_correct' => false],
                    ['text' => 'Module View Controller', 'is_correct' => false],
                    ['text' => 'Model Variable Class',   'is_correct' => false],
                ],
            ],
            [
                'text'       => 'Quelle commande crée un controller dans Laravel ?',
                'difficulty' => 'medium',
                'options'    => [
                    ['text' => 'php artisan make:controller', 'is_correct' => true],
                    ['text' => 'php artisan create:controller','is_correct' => false],
                    ['text' => 'php artisan gen:controller',  'is_correct' => false],
                    ['text' => 'php artisan new:controller',  'is_correct' => false],
                ],
            ],
            [
                'text'       => 'Qu\'est-ce qu\'une clé étrangère ?',
                'difficulty' => 'hard',
                'options'    => [
                    ['text' => 'Une colonne qui référence la clé primaire d\'une autre table', 'is_correct' => true],
                    ['text' => 'Une clé utilisée pour chiffrer les données',                  'is_correct' => false],
                    ['text' => 'Une clé primaire dupliquée',                                  'is_correct' => false],
                    ['text' => 'Un index unique sur une colonne',                             'is_correct' => false],
                ],
            ],
            [
                'text'       => 'Quel est le patron de conception utilisé par Laravel ?',
                'difficulty' => 'hard',
                'options'    => [
                    ['text' => 'MVC',      'is_correct' => true],
                    ['text' => 'MVVM',     'is_correct' => false],
                    ['text' => 'MVP',      'is_correct' => false],
                    ['text' => 'Singleton','is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $index => $data) {
            $question = Question::create([
                'text'       => $data['text'],
                'difficulty' => $data['difficulty'],
                'user_id'    => $teacher->id,
            ]);

            foreach ($data['options'] as $order => $option) {
                $question->options()->create([
                    'text'       => $option['text'],
                    'is_correct' => $option['is_correct'],
                    'order'      => $order,
                ]);
            }
        }
    }
}