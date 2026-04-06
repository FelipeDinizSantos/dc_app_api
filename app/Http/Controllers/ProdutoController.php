<?php

namespace App\Http\Controllers;

use App\Services\ProdutoService;
use App\Models\Produto;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function __construct(private readonly ProdutoService $produtoService) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor' => ['required', 'numeric', 'min:0', 'decimal:0,2']
        ]);

        $produto = $this->produtoService->criar($validated);

        return response()->json([
            'message' => 'Produto criado com sucesso.',
            'produto' => $produto,
        ], 201);
    }

    // Como é uma ação simples, preferi retornar direto do controller!
    public function index()
    {
        return Produto::paginate(10);
    }
}