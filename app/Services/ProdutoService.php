<?php

namespace App\Services;

use App\Models\Produto;
use App\Models\VendaItem;

class ProdutoService
{
    public function criar(array $dados): Produto
    {
        return Produto::create([
            'nome' => $dados['nome'],
            'valor' => $dados['valor'],
        ]);
    }

    public function atualizar(Produto $produto, array $dados): Produto
    {
        $produto->update([
            'nome' => $dados['nome'],
            'valor' => $dados['valor'],
        ]);

        return $produto;
    }

    public function deletar(int $id): void
    {
        $produto = Produto::findOrFail($id);

        $temVinculo = VendaItem::where('id_produto', $produto->id)->exists();

        if ($temVinculo) {
            throw new \Exception('Não é possível excluir o produto pois ele esta ligado a uma venda!');
        }

        $produto->delete();
    }
}
