<?php

namespace App\Services;

use App\Models\Cliente;

class ClienteService
{
    public function criar(array $dados): Cliente
    {
        return Cliente::create([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf_cnpj' => $dados['cpf_cnpj'],
        ]);
    }
}