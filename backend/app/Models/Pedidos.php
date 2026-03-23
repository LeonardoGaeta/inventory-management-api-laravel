<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    protected $primaryKey = "id_pedido";

    protected $fillable = [
        "valor_total_bruto",
        "valor_total_liquido",
        "valor_desconto",
        "valor_porc_desconto",
        "peso_bruto",
        "peso_liquido",
        "valor_total_ipi",
        "situacao",
        "fk_clientes_id"
    ];

    public function produtos_pedido()
    {
        return $this->hasMany(Produtos_Pedido::class, "fk_pedidos_id", "id_pedido");
    }

    public $timestamps = false;

    public function clientes()
    {
        return $this->belongsTo(Clientes::class, "fk_cliente_id", "id_cliente");
    }
}
