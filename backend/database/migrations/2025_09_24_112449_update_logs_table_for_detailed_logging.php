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
        Schema::table('logs', function (Blueprint $table) {
            $table->string('entidade')->nullable()->after('descricao');
            $table->unsignedBigInteger('entidade_id')->nullable()->after('entidade');
            $table->json('dados_anteriores')->nullable()->after('entidade_id'); 
            $table->json('dados_novos')->nullable()->after('dados_anteriores');
            $table->string('ip_address', 45)->nullable()->after('dados_novos');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            // Reverter as alterações
            $table->dropColumn([
                'entidade',
                'entidade_id',
                'dados_anteriores',
                'dados_novos',
                'ip_address',
                'user_agent'
            ]);
        });
    }
};
