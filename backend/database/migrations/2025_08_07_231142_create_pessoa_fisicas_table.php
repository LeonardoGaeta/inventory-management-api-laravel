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
        Schema::create('pessoa_fisicas', function (Blueprint $table) {
            $table->string("nome", 128);
            $table->date("nascimento");
            $table->char("cpf", 11);
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
        Schema::dropIfExists('pessoa_fisicas');
    }
};
