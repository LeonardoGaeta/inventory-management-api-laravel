<?php

namespace App\Http\Controllers;

use App\Models\Produtos_Pedido;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ProdutosPedidoController extends Controller
{
    private function validateItem(array $data)
    {
        try {
            $validated = validator($data, [
                "quantidade" => "required|integer|gte:1",
                "valor_ipi" => "required|decimal:0,2|gte:0",
                "valor_icms" => "required|decimal:0,2|gte:0",
                "valor_unit" => "required|decimal:0,2|gt:0",
                "valor_total" => "required|decimal:0,2|gt:0",
                "fk_pedidos_id" => "required|exists:pedidos,id_pedido",
                "fk_cod_produto" => "required|exists:produtos,cod_produto",
            ])->validate();

            return $validated;
        } catch (ValidationException $error) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $error->errors()
            ], 422);
        }
    }

    public function show_produtos_pedido(string $id_pedido)
    {
        $produtos_pedido = Produtos_Pedido::with(['produto:cod_produto,nome,peso_bruto,peso_liquido'])
            ->where("fk_pedidos_id", $id_pedido)
            ->get();

        return $produtos_pedido;
    }

    public function store(Request $request)
    {
        $produtos_pedido = [];

        foreach ($request->all() as $produto_pedido) {
            $validated = $this->validateItem($produto_pedido);

            if ($validated instanceof \Illuminate\Http\JsonResponse) {
                return $validated;
            }

            $produtos_pedido[] = Produtos_Pedido::create($validated);
        }
        return response()->json($produtos_pedido, 201);
    }

    public function update(Request $request, string $id_pedido)
    {
        $novo_produtos_pedido = [];

        $produtos_pedido = $request->all();
        $ids_recebidos = collect($produtos_pedido)
            ->pluck('id_produtos_pedido')
            ->toArray();

        $produtos_pedido_remover = Produtos_Pedido::where('fk_pedidos_id', $id_pedido)
            ->whereNotIn('id_produtos_pedido', $ids_recebidos)
            ->get();
        foreach ($produtos_pedido_remover as $produto_pedido_removido) {
            $produto_pedido_removido->delete();
        }

        foreach ($produtos_pedido as $produto_pedido) {
            $validated = $this->validateItem($produto_pedido);
            if ($validated instanceof \Illuminate\Http\JsonResponse) {
                return $validated;
            }

            if (isset($produto_pedido['id_produtos_pedido']) && Produtos_Pedido::where('id_produtos_pedido', $produto_pedido['id_produtos_pedido'])->exists()) {
                $produto_pedido_alterado = Produtos_Pedido::findOrFail($produto_pedido['id_produtos_pedido']);
                $produto_pedido_alterado->fill($validated)->save();
                $novo_produtos_pedido[] = $produto_pedido_alterado;
            } else {
                $validated['fk_pedidos_id'] = $id_pedido;
                $novo_produto_pedido = Produtos_Pedido::create($validated);
                $novo_produtos_pedido[] = $novo_produto_pedido;
            }
        }

        return response()->json($novo_produtos_pedido, status: 202);
    }

    public function destroy(string $id)
    {
        Produtos_Pedido::findOrFail($id)->delete();
        return response()->json(['message' => 'produto_pedido deletado'], 200);
    }
}
