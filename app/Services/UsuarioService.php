<?php

namespace App\Services;

use App\Models\Usuario;

class UsuarioService
{
    public function criar(array $dados): Usuario
    {
        return Usuario::create([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf_cnpj' => $dados['cpf_cnpj'],
            'senha' => $dados['senha'], 
        ]);
    }
}