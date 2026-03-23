<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $primaryKey = "id_cliente";

    protected $fillable = [
        "celular",
        "telefone",
        "email",
        "observacao",
        "tipo",
        "cep",
        "bairro",
        "rua",
        "numero",
        "cidade",
        "estado",
        "pais",
        "complemento",
        "referencia"
    ];

    public $timestamps = false; 

    public function pessoa_fisica()
    {
        return $this->hasOne(Pessoa_fisica::class, "fk_clientes_id", "id_cliente");
    }

    public function pessoa_juridica()
    {
        return $this->hasOne(Pessoa_juridica::class, "fk_clientes_id", "id_cliente");
    }

    public function pedidos()
    {
        return $this->hasMany(Pedidos::class, "fk_clientes_id", "id_cliente");
    }
}
