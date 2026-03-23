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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id("id_pedido");
            $table->decimal("valor_total_bruto", 12, 2);
            $table->decimal("valor_total_liquido", 12, 2);
            $table->decimal("valor_desconto", 12, 2);
            $table->decimal("valor_porc_desconto", 2, 8);
            $table->decimal("peso_liquido", 3, 2);
            $table->decimal("valor_total_ipi", 2, 8);
            $table->string("situacao", 64);
            $table->json('variacao')->nullable();
            $table->json('categoria')->nullable();
            $table->foreignIdFor(\App\Models\Clientes::class, "fk_clientes_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
