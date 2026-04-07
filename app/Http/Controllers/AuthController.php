<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required', 'string'],
        ]);

        try {
            $result = $this->authService->login(
                $validated['email'],
                $validated['senha']
            );

            return response()->json([
                'message' => 'Login feito com sucesso!',
                'token' => $result['token'],
                'usuario' => $result['usuario'],
            ]);

        } catch (ValidationException $erros) {
            return response()->json([
                'message' => 'Credenciais inválidas. Confirme as informações e tente novamente!',
                'erros' => $erros->errors(),
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $usuario = $this->authService->me($request->user());

        return response()->json([
            'usuario' => $usuario,
        ]);
    }
}