<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\VendaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    public function __construct(private readonly VendaService $vendaService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'cliente' => ['sometimes', 'integer', 'exists:clientes,id'],
            'data_inicial' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'data_final' => ['sometimes', 'date', 'date_format:Y-m-d', 'after_or_equal:data_inicial'],
        ]);

        $vendas = $this->vendaService->buscarTodos($request->only([
            'cliente_id',
            'data_inicial',
            'data_final',
        ]));

        return response()->json($vendas);
    }

    public function show(int $id): JsonResponse
    {
        // Já retorna automaticamente status 404 caso não encontre!
        return response()->json($this->vendaService->buscarPorId($id));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $venda = $this->vendaService->criar($request->only([
                'id_usuario',
                'id_cliente',
                'items',
                'parcelas',
            ]));

            return response()->json([
                'mensagem' => 'Venda criada com sucesso!',
                'venda' => $venda,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dados = $request->validate([
            'id_cliente' => ['required', 'integer', 'exists:clientes,id'],
            'data' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_produto' => ['required', 'integer', 'exists:produtos,id'],
            'items.*.valor_unitario' => ['required', 'numeric', 'min:0'],
            'items.*.qtd' => ['required', 'integer', 'min:1'],
        ]);

        $venda = $this->vendaService->atualizar($id, $dados);

        return response()->json($venda);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->vendaService->deletar($id);

        return response()->json([
            'mensagem' => 'Venda excluida com sucesso.'
        ]);
    }
}