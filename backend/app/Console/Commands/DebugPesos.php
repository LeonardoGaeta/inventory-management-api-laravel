<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DebugPesos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:pesos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug dos pesos dos produtos e pedidos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== DEBUG DOS PESOS ===");

        // Verificar produtos
        $this->info("\n--- PRODUTOS ---");
        $produtos = \App\Models\Produtos::select('cod_produto', 'nome', 'peso_bruto', 'peso_liquido')->get();
        foreach ($produtos as $produto) {
            $this->line("{$produto->cod_produto} - {$produto->nome} - Bruto: {$produto->peso_bruto} - Líquido: {$produto->peso_liquido}");
        }

        // Verificar Produtos de pedido
        $this->info("\n--- Produtos DE PEDIDO ---");
        $produtos_pedido = \App\Models\Produtos_Pedido::with('produto')->get();
        foreach ($produtos_pedido as $produto_pedido) {
            $produto = $produto_pedido->produto;
            $pesoTotalBruto = ($produto->peso_bruto ?? 0) * $produto_pedido->quantidade;
            $this->line("Pedido {$produto_pedido->fk_pedidos_id} - Produto {$produto_pedido->fk_cod_produto} - Qtd: {$produto_pedido->quantidade} - Peso Total Bruto: {$pesoTotalBruto}");
        }

        return 0;
    }
}
