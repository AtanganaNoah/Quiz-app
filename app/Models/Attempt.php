<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'max_score',
        'status',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────────────

    /** Étudiant qui a fait cette tentative */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Quiz concerné */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /** Réponses données lors de cette tentative */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    /** Pourcentage de réussite */
    public function percentage(): float
    {
        if ($this->max_score === 0) return 0;

        return round(($this->score / $this->max_score) * 100, 2);
    }

    /** Durée en secondes */
    public function durationInSeconds(): ?int
    {
        if (! $this->finished_at) return null;

        return $this->started_at->diffInSeconds($this->finished_at);
    }

    /**
     * Calculer et enregistrer le score final.
     * Appelle cette méthode quand l'étudiant termine son quiz.
     */
    public function calculateScore(): void
    {
        $correctCount = $this->responses()->where('is_correct', true)->count();

        // Récupère les points par question depuis la pivot
        $totalScore = 0;
        foreach ($this->responses()->where('is_correct', true)->with('question')->get() as $response) {
            $pivot = $this->quiz->questions()
                                ->where('question_id', $response->question_id)
                                ->first()?->pivot;

            $totalScore += $pivot?->points ?? 1;
        }

        $this->update([
            'score'       => $totalScore,
            'max_score'   => $this->quiz->maxScore(),
            'status'      => 'completed',
            'finished_at' => now(),
        ]);
    }
}