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
        Schema::create('pessoa_juridicas', function (Blueprint $table) {
            $table->string("razao_social", 128);
            $table->string("nome_fantasia", 128);
            $table->char("cnpj", 14);
            $table->string("referencia_url", 128)->nullable();
            $table->string("inscricao_municipal", 32);
            $table->string("inscricao_estadual", 32);
            $table->boolean("isento")->default(false);
            // $table->foreignIdFor(\App\Models\Clientes::class, "fk_clientes_id");
            $table->foreignId("fk_clientes_id")
                    ->references("id_cliente")
                    ->on("clientes")
                    ->onDelete("cascade");

            $table->primary("fk_clientes_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_juridicas');
    }
};
