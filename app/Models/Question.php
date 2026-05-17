<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'difficulty',
        'user_id',
    ];

    // ─── Relations ────────────────────────────────────────────────

   

    /** Auteur de la question */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Options / réponses possibles */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->orderBy('order');
    }

    /** Quiz qui utilisent cette question */
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')
                    ->withPivot('points', 'order')
                    ->withTimestamps();
    }

    /** Réponses des étudiants à cette question */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    /** Retourne l'option correcte */
    public function correctOption(): ?Option
    {
        return $this->options()->where('is_correct', true)->first();
    }
}