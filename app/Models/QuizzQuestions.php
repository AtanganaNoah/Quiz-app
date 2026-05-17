<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Model pivot explicite pour quiz_questions.
 * Utile si tu veux accéder directement à la table pivot
 * ou y attacher de la logique métier.
 */
class QuizQuestion extends Pivot
{
    protected $table = 'quiz_questions';

    protected $fillable = [
        'quiz_id',
        'question_id',
        'points',
        'order',
    ];

    // ─── Relations ────────────────────────────────────────────────

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
