<?php

namespace App\Services;

use App\Models\Pagamento;
use App\Models\Parcela;
use App\Models\Venda;
use App\Models\VendaItem;

class VendaService
{
    // Listagem é feita com base em filtros que são fornecidos pelo frontend na queryString
    public function buscarTodos(array $filtros)
    {
        $query = Venda::with(['pagamento.parcelas', 'cliente', 'usuario']);

        if (! empty($filtros['cliente_id'])) {
            $query->where('id_cliente', $filtros['cliente_id']);
        }

        if (! empty($filtros['data_inicial'])) {
            $query->whereDate('data', '>=', $filtros['data_inicial']);
        }

        if (! empty($filtros['data_final'])) {
            $query->whereDate('data', '<=', $filtros['data_final']);
        }

        return $query->orderBy('data', 'desc')->get();
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

        // Tive que usar o round para arredondar o valor a duas casas decimais, pois, os valores com minusculas variações estavam sendo somandos de forma distinta da do front
        // Gerando uma inconsistencia no valor total!
        $totalParcelas = round(array_sum(array_column($dados['parcelas'], 'valor')), 2);
        $totalVenda = round($totalVenda, 2);

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

    // Esse método somente atualiza cliente, usuario e/ou data. Pode ser incrementando depois se houver demanda
    public function atualizar(int $id, array $dados): Venda
    {
        $venda = Venda::findOrFail($id);

        $venda->update([
            'id_cliente' => $dados['id_cliente'],
            'id_usuario' => $dados['id_usuario'],
            'data' => $dados['data'],
        ]);

        return $venda->load(['cliente', 'usuario', 'pagamento']);
    }

    // Como envolve pagamentos e cobranças esses delets são todos softDeletes.
    // Verificar depois questão das parcelas orfãos...
    public function deletar(int $id): void
    {
        $venda = Venda::findOrFail($id);
        $venda->itens()->delete();
        $venda->pagamento()->delete();
        $venda->delete();
    }
}
