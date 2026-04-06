<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nome', 'email', 'cpf_cnpj'])]
class Cliente extends Model
{
     protected function casts(): array
    {
        return [
            'nome' => 'string',
            'email' => 'string',
            'cpf_cnpj' => 'string',
        ];
    }
}
