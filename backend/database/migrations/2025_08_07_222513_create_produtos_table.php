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
        Schema::create('produtos', function (Blueprint $table) {
            $table->string("cod_produto")->primary();
            $table->string("nome", 128);
            $table->string("marca", 64);
            $table->integer("quantidade_estoque");
            $table->decimal("valor_venda", 12, 2);
            $table->decimal("valor_custo", 12, 2);
            $table->decimal("peso_bruto", 3, 2);
            $table->decimal("peso_liquido", 3, 2);
            $table->string("observacoes", 128)->nullable();
            $table->json('variacao')->nullable();
            $table->json('categoria')->nullable();
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->string('observacoes', 128)->nullable()->change();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
