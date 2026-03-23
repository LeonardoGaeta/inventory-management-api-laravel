<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Models
use App\Models\Clientes;
use App\Models\Pedidos;
use App\Models\Produtos;
use App\Models\Pessoa_fisica;
use App\Models\Pessoa_juridica;
use App\Models\Produtos_Pedido;
use App\Observers\ProdutosPedidoObserver;
// Observers
use App\Observers\LogObserver;
use App\Observers\PedidosObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Clientes::observe(LogObserver::class);
        Produtos_Pedido::observe(LogObserver::class);
        Produtos_Pedido::observe(ProdutosPedidoObserver::class);
        Pedidos::observe(LogObserver::class);
        Pedidos::observe(PedidosObserver::class);
        Produtos::observe(LogObserver::class);
        Pessoa_fisica::observe(LogObserver::class);
        Pessoa_juridica::observe(LogObserver::class);
    }
}
