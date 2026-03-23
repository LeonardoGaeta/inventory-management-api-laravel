<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa_juridica extends Model
{
        protected $primaryKey = 'fk_clientes_id';
    public $incrementing = false;

    protected $fillable = [
        "razao_social",
        "nome_fantasia",
        "cnpj",
        "referencia_url",
        "inscricao_municipal",
        "inscricao_estadual",
        "isento",
        "fk_clientes_id"
    ];

    public $timestamps = false; 

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, "fk_clientes_id", "id_cliente");
    }
}
