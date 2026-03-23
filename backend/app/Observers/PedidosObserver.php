<?php

namespace App\Observers;

use App\Models\Produtos_Pedido;
use App\Models\Pedidos;
use App\Models\Produtos;

class PedidosObserver
{
    /**
     * Handle the Pedidos "updated" event.
     */
    public function updated(Pedidos $pedidos): void
    {
        $situacaoAntiga = $pedidos->getOriginal('situacao');
        $situacaoAtual = $pedidos->situacao;

        if ($situacaoAntiga != $situacaoAtual && $situacaoAtual == 'finalizado') {
            $produtos_pedido = Produtos_Pedido::where('fk_pedidos_id', $pedidos->id_pedido)->get(['quantidade', 'fk_cod_produto']);

            foreach ($produtos_pedido as $produto_pedido) {
                $produto = Produtos::findOrFail($produto_pedido->fk_cod_produto);
                $produto->quantidade_estoque -= $produto_pedido->quantidade;
                $produto->save();
            }
        } elseif ($situacaoAntiga != $situacaoAtual && $situacaoAntiga == 'finalizado') {
            $produtos_pedido = Produtos_Pedido::where('fk_pedidos_id', $pedidos->id_pedido)->get(['quantidade', 'fk_cod_produto']);

            foreach ($produtos_pedido as $produto_pedido) {
                $produto = Produtos::findOrFail($produto_pedido->fk_cod_produto);
                $produto->quantidade_estoque += $produto_pedido->quantidade;
                $produto->save();
            }
        }
    }
}
