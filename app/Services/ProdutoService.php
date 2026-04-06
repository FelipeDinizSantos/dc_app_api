<?php

namespace App\Services;

use App\Models\Produto;

class ProdutoService
{
    public function criar(array $dados): Produto
    {
        return Produto::create([
            'nome' => $dados['nome'],
            'valor' => $dados['valor'],
        ]);
    }
}