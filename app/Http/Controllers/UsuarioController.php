<?php

namespace App\Http\Controllers;

use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function __construct(private readonly UsuarioService $usuarioService) {}

    public function index(): JsonResponse
    {
        $usuarios = $this->usuarioService->listar();

        return response()->json($usuarios);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:usuarios,email'],
            'cpf_cnpj' => ['required', 'string', 'max:18', 'unique:usuarios,cpf_cnpj'],
            'senha' => ['required', 'string', 'min:8'],
            'senha_confirmacao' => ['required', 'same:senha'],
        ]);

        $usuario = $this->usuarioService->criar($validated);

        return response()->json([
            'message' => 'Usuário criado com sucesso.',
            'usuario' => $usuario,
        ], 201);
    }
}
