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
        Schema::create('produtos__pedidos', function (Blueprint $table) {
            $table->id("id_produtos_pedido");
            $table->integer("quantidade");
            $table->decimal("valor_ipi", 3, 2);
            $table->decimal("valor_icms", 3, 2);
            $table->decimal("valor_unit", 12, 2);
            $table->decimal("valor_total", 12, 2);
            $table->foreignIdFor(\App\Models\Pedidos::class, "fk_pedidos_id")->constrained()->cascadeOnDelete();
            $table->string("fk_cod_produto");
            $table->foreign("fk_cod_produto")
                ->references("cod_produto")
                ->on("produtos")
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos__pedidos');
    }
};
