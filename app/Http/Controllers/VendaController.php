<?php

namespace App\Http\Controllers;

use App\Services\VendaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    public function __construct(private readonly VendaService $vendaService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'cliente_id' => ['sometimes', 'integer', 'exists:clientes,id'],
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
        return response()->json($this->vendaService->buscarPorId($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_cliente' => ['nullable', 'integer', 'exists:clientes,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_produto' => ['required', 'integer', 'exists:produtos,id'],
            'items.*.valor_unitario' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'items.*.qtd' => ['required', 'integer', 'min:1'],
            'parcelas' => ['required', 'array', 'min:1'],
            'parcelas.*.forma_de_pagamento' => ['required', 'string', 'in:dinheiro,cartao_credito,cartao_debito,pix,boleto'],
            'parcelas.*.valor' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'parcelas.*.data_vencimento' => ['required', 'date', 'date_format:Y-m-d'],
            'parcelas.*.data_pagamento' => ['nullable', 'date', 'date_format:Y-m-d'],
        ]);

        try {
            $venda = $this->vendaService->criar($validated);

            return response()->json([
                'message' => 'Venda criada com sucesso!',
                'venda' => $venda,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'id_cliente' => ['sometimes', 'nullable', 'integer', 'exists:clientes,id'],
            'data' => ['sometimes', 'date', 'date_format:Y-m-d H:i:s'],
            'itens' => ['sometimes', 'array', 'min:1'],
            'itens.*.id_produto' => ['required_with:itens', 'integer', 'exists:produtos,id'],
            'itens.*.valor_unitario' => ['required_with:itens', 'numeric', 'min:0', 'decimal:0,2'],
            'itens.*.qtd' => ['required_with:itens', 'integer', 'min:1'],
            'parcelas' => ['sometimes', 'array', 'min:1'],
            'parcelas.*.forma_de_pagamento' => ['required_with:parcelas', 'string', 'in:dinheiro,cartao_credito,cartao_debito,pix,boleto'],
            'parcelas.*.valor' => ['required_with:parcelas', 'numeric', 'min:0', 'decimal:0,2'],
            'parcelas.*.data_vencimento' => ['required_with:parcelas', 'date', 'date_format:Y-m-d'],
            'parcelas.*.data_pagamento' => ['nullable', 'date', 'date_format:Y-m-d'],
        ]);

        $venda = $this->vendaService->atualizar($id, $validated);

        return response()->json([
            'message' => 'Venda atualizada com sucesso.',
            'venda' => $venda,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->vendaService->deletar($id);

            return response()->json([
                'message' => 'Venda excluída com sucesso.',
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Venda não encontrada.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível excluir a venda.',
            ], 500);
        }
    }
}
