<?php

namespace App\Http\Controllers;

use App\Services\ClienteService;
use App\Models\Cliente;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(private readonly ClienteService $clienteService) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:clientes,email'],
            'cpf_cnpj' => ['required', 'string', 'max:18', 'unique:clientes,cpf_cnpj'],
        ]);

        $cliente = $this->clienteService->criar($validated);

        return response()->json([
            'message' => 'Cliente criado com sucesso.',
            'cliente' => $cliente,
        ], 201);
    }

    // Como é uma ação simples, preferi retornar direto do controller!
    public function index()
    {
        return Cliente::all();
    }
}