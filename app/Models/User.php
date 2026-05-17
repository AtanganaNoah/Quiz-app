<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Relations ────────────────────────────────────────────────

    /*Quiz créés par cet utilisateur */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /*Questions créées par cet utilisateur */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /** Tentatives de quiz de cet utilisateur */
    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}