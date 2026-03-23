<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produtos_Pedido extends Model
{
    protected $primaryKey = "id_produtos_pedido";

    protected $fillable = [
        "fk_cod_produto",
        "quantidade",
        "valor_ipi",
        "valor_icms",
        "valor_unit",
        "valor_total",
        "fk_pedidos_id"
    ];

    public $timestamps = false;

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, "fk_pedidos_id", "id_pedido");
    }

    public function produto()
    {
        return $this->belongsTo(Produtos::class, "fk_cod_produto", "cod_produto");
    }
}
