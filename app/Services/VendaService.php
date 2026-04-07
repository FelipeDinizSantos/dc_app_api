<?php

namespace App\Services;

use App\Models\Venda;
use App\Models\VendaItem;
use App\Models\Pagamento;
use App\Models\Parcela;

class VendaService
{
    // Listagem é feita com base em filtros que são fornecidos pelo frontend na queryString
    public function buscarTodos(array $filtros)
    {
        $query = Venda::query();

        if (!empty($filtros['cliente_id'])) {
            $query->where('cliente_id', $filtros['cliente_id']);
        }

        // Range de data
        if (!empty($filtros['data_inicial'])) {
            $query->whereDate('created_at', '>=', $filtros['data_inicial']);
        }

        if (!empty($filtros['data_final'])) {
            $query->whereDate('created_at', '<=', $filtros['data_final']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function buscarPorId(int $id): Venda
    {
        return Venda::with('itens')->findOrFail($id);
    }

    public function criar(array $dados): Venda
    {
        $totalVenda = 0;
        foreach ($dados['items'] as $item) {
            $totalVenda += $item['valor_unitario'] * $item['qtd'];
        }

        $venda = Venda::create([
            'id_usuario' => auth('sanctum')->id(),
            'id_cliente' => $dados['id_cliente'],
            'valor_total' => $totalVenda,
            'data' => now(),
        ]);

        $items = [];
        foreach ($dados['items'] as $item) {
            $items[] = [
                'id_venda' => $venda->id,
                'id_produto' => $item['id_produto'],
                'valor_unitario' => $item['valor_unitario'],
                'qtd' => $item['qtd'],
                'sub_total' => $item['valor_unitario'] * $item['qtd'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        VendaItem::insert($items);

        $pagamento = Pagamento::create([
            'id_venda' => $venda->id,
            'forma_de_pagamento' => 'personalizado',
            'valor' => $totalVenda,
        ]);

        $totalParcelas = 0;
        foreach ($dados['parcelas'] as $p) {
            $totalParcelas += $p['valor'];
        }

        if ($totalParcelas != $totalVenda) {
            throw new \Exception('Soma das parcelas diferente do total da venda!');
        }

        $parcelas = [];
        foreach ($dados['parcelas'] as $index => $parcela) {
            $parcelas[] = [
                'id_pagamento' => $pagamento->id,
                'numero_da_parcela' => $index + 1,                  
                'forma_de_pagamento' => $parcela['forma_de_pagamento'], 
                'valor' => $parcela['valor'],
                'data_vencimento' => $parcela['data_vencimento'],
                'data_pagamento' => $parcela['data_pagamento'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Parcela::insert($parcelas);

        return $venda->load(['itens', 'pagamento.parcelas']);
    }

    public function atualizar(int $id, array $dados): Venda
    {
        $venda = Venda::findOrFail($id);

        $totalVenda = 0;
        foreach ($dados['items'] as $item) {
            $totalVenda += $item['valor_unitario'] * $item['qtd'];
        }

        $venda->update([
            'id_cliente' => $dados['id_cliente'],
            'valor_total' => $totalVenda,
            'data' => $dados['data'],
        ]);

        // Recria o conjunto de itens relacionados a venda e adiciona os novos editados, removendo os antigos
        // Ou seja, remove tudo e recria!
        $venda->itens()->delete();

        $items = [];
        foreach ($dados['items'] as $item) {
            $items[] = [
                'id_venda' => $venda->id,
                'id_produto' => $item['id_produto'],
                'valor_unitario' => $item['valor_unitario'],
                'qtd' => $item['qtd'],
                'sub_total' => $item['valor_unitario'] * $item['qtd'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        VendaItem::insert($items);
        return $venda->load('itens');
    }

    public function deletar(int $id): void
    {
        $venda = Venda::findOrFail($id);
        $venda->itens()->delete();
        $venda->delete();
    }
}