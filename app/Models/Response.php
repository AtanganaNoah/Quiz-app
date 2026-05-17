<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'option_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────────

    /** Tentative liée */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    /** Question concernée */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /** Option choisie par l'étudiant */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    // ─── Boot : auto-calculer is_correct à la création ────────────

    protected static function booted(): void
    {
        static::creating(function (Response $response) {
            // Vérifie automatiquement si la réponse est correcte
            $response->is_correct = Option::where('id', $response->option_id)
                                          ->value('is_correct') ?? false;
        });
    }
}