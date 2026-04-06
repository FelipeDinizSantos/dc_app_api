<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['id_usuario', 'id_cliente', 'valor_total', 'data'])]
class Venda extends Model
{
    protected function casts(): array
    {
        return [
            'valor_total' => 'decimal:2',
            'data' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(VendaItem::class, 'id_venda');
    }

    public function pagamento(): HasOne
    {
        return $this->hasOne(Pagamento::class, 'id_venda');
    }
}
