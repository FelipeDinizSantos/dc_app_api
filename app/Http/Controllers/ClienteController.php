<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\ClienteService;
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

    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('nome')) {
            $nome = $request->input('nome');
            $query->where('nome', 'like', "%{$nome}%");
        }

        // Solução para evitar ter que criar um método 'show'...
        if ($request->filled('id')) {
            $id = $request->input('id');
            $query->where('id', $id);
        }

        $clientes = $query->get();

        return response()->json($clientes);
    }
}
