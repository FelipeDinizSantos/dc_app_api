<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Venda;

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

    public function atualizar(Cliente $cliente, array $dados): Cliente
    {
        $cliente->update([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf_cnpj' => $dados['cpf_cnpj'],
        ]);

        return $cliente;
    }

    public function deletar(int $id): void
    {
        $cliente = Cliente::findOrFail($id);
        $temVendas = Venda::where('id_cliente', $cliente->id)->exists();

        if ($temVendas) {
            throw new \Exception('Não é possível excluir o cliente pois ele possui uma venda em seu nome. Exclua a venda antes!');
        }

        $cliente->delete();
    }
}
