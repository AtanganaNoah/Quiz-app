<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'time_limit',
        'is_published',
        'user_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────────

    /** Créateur du quiz */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Questions liées à ce quiz via la table pivot quiz_questions.
     * On récupère aussi les colonnes supplémentaires de la pivot (points, order).
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
                    ->withPivot('points', 'order')
                    ->orderByPivot('order')
                    ->withTimestamps();
    }

    /** Tentatives faites sur ce quiz */
    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    /** Score maximum possible pour ce quiz */
    public function maxScore(): int
    {
        return $this->questions()->sum('quiz_questions.points');
    }
}