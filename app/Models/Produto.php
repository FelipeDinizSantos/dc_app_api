<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nome', 'valor'])]
#[Hidden(['ativo'])]
class Produto extends Model
{
    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
        ];
    }
}
