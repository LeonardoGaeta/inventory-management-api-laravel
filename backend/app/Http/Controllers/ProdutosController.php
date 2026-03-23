<?php

namespace App\Http\Controllers;

use App\Models\Produtos;

use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    private function validateProduct(Request $request, $id = null)
    {
        try {
            $validated = $request->validate([
                "cod_produto" => [
                    "required",
                    "string",
                    Rule::unique('produtos', "cod_produto")->ignore($id, "cod_produto")
                ],
                "nome" => "required|string|min:2|max:128",
                "marca" => "required|string|min:2|max:64",
                "quantidade_estoque" => "required|integer",
                "valor_custo" => "required|decimal:0,2|min:0",
                "valor_venda" => "required|decimal:0,2|gt:valor_custo",
                "peso_bruto" => "required|decimal:0,2|min:0|",
                "peso_liquido" => "required|decimal:0,2|min:0|lte:peso_bruto",
                "observacoes" => "nullable|string|max:128",
                "variacao" => "nullable|array",
                "variacao.*" => "string|max:256",
                "categoria" => "nullable|array",
                "categoria.*" => "string|max:128",
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
        $produtos = Produtos::all();
        return $produtos;
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        if ($validated instanceof \Illuminate\Http\JsonResponse) {
            return $validated;
        }

        $produto = Produtos::create($validated);
        return response()->json($produto, 201);
    }

    public function show(string $id)
    {
        return Produtos::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $produto = Produtos::findOrFail($id);

        $validated = $this->validateProduct($request, $id);

        if ($validated instanceof \Illuminate\Http\JsonResponse) {
            return $validated;
        }

        $produto->fill($validated)->save();
        return response()->json($produto, 202);
    }

    public function destroy(string $id)
    {
        Produtos::findOrFail($id)->delete();
        return response()->json(['message' => 'Produto deletado'], 200);
    }
}
