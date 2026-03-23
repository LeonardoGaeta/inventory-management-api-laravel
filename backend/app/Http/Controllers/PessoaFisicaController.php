<?php

namespace App\Http\Controllers;

use App\Models\Pessoa_fisica;
use Illuminate\Http\Request;

class PessoaFisicaController extends Controller
{
    public function index()
    {
        $clientes = Pessoa_fisica::join("clientes", "pessoa_fisicas.fk_clientes_id", "=", "clientes.id_cliente")
            ->select('pessoa_fisicas.*', 'clientes.*')
            ->get();

        return $clientes;
    }

    public function show(string $id)
    {
        $cliente = Pessoa_fisica::where("fk_clientes_id", "=", $id)
            ->join("clientes", "pessoa_fisicas.fk_clientes_id", "=", "clientes.id_cliente")
            ->firstOrFail();

        return $cliente;
    }
}
