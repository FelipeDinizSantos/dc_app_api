<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_pagamento', 'numero_da_parcela', 'valor', 'forma_de_pagamento', 'data_vencimento', 'data_pagamento'])]
class Parcela extends Model
{
    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'numero' => 'integer',
            'forma_de_pagamento' => 'string',
            'data_vencimento' => 'date',
            'data_pagamento' => 'date',
        ];
    }

    public function pagamento(): BelongsTo
    {
        return $this->belongsTo(Pagamento::class, 'id_pagamento');
    }
}
