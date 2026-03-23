<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use App\Models\Clientes;
use App\Models\Produtos_Pedido;

// use Illuminate\Validation\ValidationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    private function validate(Request $request)
    {
        try {
            $validated = $request->validate([
                "valor_total_bruto" => "required|decimal:0,2|min:0",
                "valor_total_liquido" => "required|decimal:0,2|min:0|lte:valor_total_bruto",
                "valor_desconto" => "nullable|decimal:0,2|min:0",
                "valor_porc_desconto" => "nullable|decimal:0,8|min:0",
                "peso_liquido" => "required|decimal:0,2|min:0",
                "valor_total_ipi" => "nullable|decimal:0,8|min:0",
                "situacao" => "required|string|max:64",
                "fk_clientes_id" => "required|integer|exists:clientes,id_cliente",
            ]);

            return $validated;
        } catch (ValidationException $error) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $error->errors()
            ], 422);
        }
    }

    public function index()
    {
        $pedidos = Pedidos::leftJoin("pessoa_juridicas", "pedidos.fk_clientes_id", "=", "pessoa_juridicas.fk_clientes_id")
            ->leftJoin("pessoa_fisicas", "pedidos.fk_clientes_id", "=", "pessoa_fisicas.fk_clientes_id")
            ->select(
                "pedidos.*",
                DB::raw("COALESCE(pessoa_fisicas.nome, pessoa_juridicas.nome_fantasia) as nome")
            )
            ->with(['Produtos_Pedido.produto'])
            ->get()
            ->map(function ($pedido) {
                $pesoTotalBruto = $pedido->Produtos_Pedido->sum(function ($item) {
                    return ($item->produto->peso_bruto ?? 0) * $item->quantidade;
                });

                $pesoTotalLiquido = $pedido->Produtos_Pedido->sum(function ($item) {
                    return ($item->produto->peso_liquido ?? 0) * $item->quantidade;
                });

                // Atualizar os campos calculados
                $pedido->peso_bruto = $pesoTotalBruto;
                $pedido->peso_liquido = $pesoTotalLiquido;

                return $pedido;
            });

        return $pedidos;
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request);

        if ($validated instanceof \Illuminate\Http\JsonResponse) {
            return $validated;
        }

        $pedido = Pedidos::create($validated);
        return response()->json($pedido, 201);
    }

    public function show(string $id)
    {
        $pedido = Pedidos::where("id_pedido", $id)
            ->leftJoin("pessoa_juridicas", "pedidos.fk_clientes_id", "=", "pessoa_juridicas.fk_clientes_id")
            ->leftJoin("pessoa_fisicas", "pedidos.fk_clientes_id", "=", "pessoa_fisicas.fk_clientes_id")
            ->select(
                "pedidos.*",
                DB::raw("COALESCE(pessoa_fisicas.nome, pessoa_juridicas.nome_fantasia) as nome")
            )
            ->with(['Produtos_Pedido.produto'])
            ->firstOrFail();

        $pesoTotalBruto = $pedido->Produtos_Pedido->sum(function ($item) {
            return ($item->produto->peso_bruto ?? 0) * $item->quantidade;
        });

        $pesoTotalLiquido = $pedido->Produtos_Pedido->sum(function ($item) {
            return ($item->produto->peso_liquido ?? 0) * $item->quantidade;
        });

        $pedido->peso_bruto = $pesoTotalBruto;
        $pedido->peso_liquido = $pesoTotalLiquido;

        return $pedido;
    }

    public function update(Request $request, string $id)
    {
        $pedido = Pedidos::where('id_pedido', $id)->firstOrFail();

        $validated = $this->validate($request);

        if ($validated instanceof \Illuminate\Http\JsonResponse) {
            return $validated;
        }

        $pedido->fill($validated)->save();
        return response()->json($pedido, 202);
    }

    public function destroy(string $id)
    {
        Pedidos::where('id_pedido', $id)->firstOrFail()->delete();
        return response()->json(['message' => 'Pedido deletado'], 200);
    }
}
