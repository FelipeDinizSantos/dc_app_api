<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['nome', 'valor'])]
#[Hidden(['ativo'])]
class Produto extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
        ];
    }
}
