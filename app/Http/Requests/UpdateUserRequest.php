<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin peut modifier n'importe qui, un user peut modifier son propre profil
        return $this->user()?->isAdmin()
            || $this->user()?->id === $this->route('user')?->id;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'     => ['sometimes', 'string', 'min:2', 'max:100'],
            'email'    => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['sometimes', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            // Seul un admin peut changer le rôle
            'role'     => ['sometimes', Rule::in(['admin', 'teacher', 'student']), Rule::when(
                ! $this->user()?->isAdmin(),
                ['prohibited']
            )],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'   => 'Cette adresse email est déjà utilisée.',
            'role.prohibited'=> 'Vous n\'êtes pas autorisé à changer le rôle.',
            'role.in'        => 'Le rôle doit être admin, teacher ou student.',
        ];
    }
}
