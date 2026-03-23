<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestApiPedidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api-pedidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a API de pedidos e mostra os pesos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new \App\Http\Controllers\PedidosController();
        $pedidos = $controller->index();

        $this->info("=== TESTE API PEDIDOS ===");

        foreach ($pedidos as $pedido) {
            $this->line("Pedido {$pedido->id_pedido} - Cliente: {$pedido->nome}");
            $this->line("  Peso Bruto: {$pedido->peso_bruto} kg");
            $this->line("  Peso Líquido: {$pedido->peso_liquido} kg");
            $this->line("  Valor Total Bruto: R$ {$pedido->valor_total_bruto}");
            $this->line("---");
        }

        return 0;
    }
}
