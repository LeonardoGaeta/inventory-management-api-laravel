<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa_fisica extends Model
{
    protected $primaryKey = 'fk_clientes_id';
    public $incrementing = false;

    protected $fillable = [
        "nome",
        "nascimento",
        "cpf",
        "fk_clientes_id"
    ];

    public $timestamps = false; 

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, "fk_clientes_id", "id_cliente");
    }
}
