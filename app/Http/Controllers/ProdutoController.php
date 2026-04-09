<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function __construct(private readonly ProdutoService $produtoService) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
        ]);

        $produto = $this->produtoService->criar($validated);

        return response()->json([
            'message' => 'Produto criado com sucesso.',
            'produto' => $produto,
        ], 201);
    }

    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->filled('nome')) {
            $nome = $request->input('nome');
            $query->where('nome', 'like', "%{$nome}%");
        }

        $produtos = $query->get();

        return response()->json($produtos);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
        ]);

        $produto = Produto::findOrFail($id);

        $produto = $this->produtoService->atualizar($produto, $validated);

        return response()->json([
            'message' => 'Produto atualizado com sucesso.',
            'produto' => $produto,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->produtoService->deletar($id);

            return response()->json([
                'message' => 'Produto excluído com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
