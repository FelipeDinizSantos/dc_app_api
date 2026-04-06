<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $email, string $senha): array
    {
        $usuario = Usuario::where('email', $email)->first();

        if (!$usuario || !Hash::check($senha, $usuario->senha)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas. Confirme as informações e tente novamente!'],
            ]);
        }

        $usuario->tokens()->delete();
        $token = $usuario->createToken('x-token')->plainTextToken;

        return [
            'usuario' => $usuario,
            'token'   => $token,
        ];
    }

    public function logout(Usuario $usuario): void
    {
        $usuario->currentAccessToken()->delete();
    }

    public function me(Usuario $usuario): Usuario
    {
        return $usuario;
    }
}