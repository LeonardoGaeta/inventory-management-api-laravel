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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id("id_cliente");
            $table->string("celular", 32);
            $table->string("telefone", 32);
            $table->string("email", 64);
            $table->string("observacao", 128)->nullable();
            $table->enum("tipo", ["pf", "pj"]);
            $table->string("cep", 16)->nullable();
            $table->string("bairro", 128);
            $table->string("rua", 128);
            $table->string("numero", 8);
            $table->string("cidade", 128);
            $table->char("estado", 2);
            $table->string("pais", 128);
            $table->string("complemento", 128)->nullable();
            $table->string("referencia", 128)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
