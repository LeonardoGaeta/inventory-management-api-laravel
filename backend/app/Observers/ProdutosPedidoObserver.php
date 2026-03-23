<?php

namespace App\Observers;

use App\Models\Produtos_Pedido;
use App\Models\Pedidos;
use App\Models\Produtos;

class ProdutosPedidoObserver
{
    /**
     * Handle the Produtos_Pedido "created" event.
     */
    public function created(Produtos_Pedido $produtos_pedido): void
    {
        $pedido_situacao = Pedidos::findOrFail($produtos_pedido->fk_pedidos_id)->situacao;

        if ($pedido_situacao == 'finalizado') {
            $produto = Produtos::findOrFail($produtos_pedido->fk_cod_produto);
            $produto->quantidade_estoque -= $produtos_pedido->quantidade;
            $produto->save();
        }
    }

    /**
     * Handle the Produtos_Pedido "updated" event.
     */
    public function updated(Produtos_Pedido $produtos_pedido): void
    {
        $quantidadeAntiga = $produtos_pedido->getOriginal('quantidade');
        $quantidadeNova = $produtos_pedido->quantidade;

        if ($quantidadeAntiga != $quantidadeNova) {
            $pedido_situacao = Pedidos::findOrFail($produtos_pedido->fk_pedidos_id)->situacao;

            if ($pedido_situacao == 'finalizado') {
                $produto = Produtos::findOrFail($produtos_pedido->fk_cod_produto);
                $produto->quantidade_estoque -= -$quantidadeAntiga + $quantidadeNova;
                $produto->save();
            }
        }
    }
}
