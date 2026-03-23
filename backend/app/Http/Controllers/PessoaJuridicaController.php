<?php

namespace App\Http\Controllers;

use App\Models\Pessoa_juridica;
use Illuminate\Http\Request;

class PessoaJuridicaController extends Controller
{
    public function index()
    {
        $clientes = Pessoa_juridica::join("clientes", "pessoa_juridicas.fk_clientes_id", "=", "clientes.id_cliente")
            ->select("pessoa_juridicas.*", "clientes.*")
            ->get();

        return $clientes;
    }

    public function show(string $id)
    {
        $cliente = Pessoa_juridica::where("fk_clientes_id", "=", $id)
            ->join("clientes", "pessoa_juridicas.fk_clientes_id", "=", "clientes.id_cliente")
            ->firstOrFail();

        return $cliente;
    }
}
