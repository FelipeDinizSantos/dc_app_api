<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function listar(): Collection
    {
        return Usuario::all();
    }

    public function criar(array $dados): Usuario
    {
        return Usuario::create([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf_cnpj' => $dados['cpf_cnpj'],
            'senha' => Hash::make($dados['senha']),
        ]);
    }
}
