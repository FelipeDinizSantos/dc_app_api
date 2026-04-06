<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pagamento')->constrained('pagamentos');
            $table->tinyInteger('numero_da_parcela');
            $table->decimal('valor', 10, 2);
            $table->enum('forma_de_pagamento', ['dinheiro','cartao_credito','cartao_debito','pix','boleto']);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcelas');
    }
};
