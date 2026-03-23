<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    protected $primaryKey = "cod_produto";
    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        "cod_produto",
        "nome",
        "marca",
        "quantidade_estoque",
        "valor_venda",
        "valor_custo",
        "peso_bruto",
        "peso_liquido",
        "observacoes",
        "variacao",
        "categoria"
    ];
    protected $casts = [
        'variacao' => 'array',
        'categoria' => 'array',
    ];

    public function produtos_pedido()
    {
        return $this->hasMany(Produtos_Pedido::class, "fk_cod_produto", "cod_produto");
    }

    public $timestamps = false;
}
