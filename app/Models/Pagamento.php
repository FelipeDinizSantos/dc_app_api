<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['id_venda', 'forma_de_pagamento', 'valor'])]
class Pagamento extends Model
{
    protected function casts(): array
    {
        return [
            'forma_de_pagamento' => 'string',
            'valor' => 'decimal:2',
        ];
    }

    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class, 'id_venda');
    }

    public function parcelas(): HasMany
    {
        return $this->hasMany(Parcela::class, 'id_pagamento');
    }
}
