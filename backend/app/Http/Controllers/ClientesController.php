<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Pessoa_fisica;
use App\Models\Pessoa_juridica;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientesController extends Controller
{
    // Validação
    private function validateClient(Request $request, $id = null)
    {
        try {
            $validated = $request->validate([
                // Validação Cliente
                "cliente.celular" => [
                    "required",
                    "string",
                    "max:32",
                    Rule::unique('clientes', 'celular')->ignore($id, 'id_cliente')
                ],
                "cliente.telefone" => "nullable|string|max:32",
                "cliente.email" => [
                    "required",
                    "string",
                    "email",
                    "max:64",
                    Rule::unique('clientes', 'email')->ignore($id, 'id_cliente')
                ],
                "cliente.observacao" => "nullable|string|max:128",
                "cliente.tipo" => "required|in:pf,pj",
                "cliente.cep" => "required|string|max:16",
                "cliente.bairro" => "required|string|max:128",
                "cliente.rua" => "required|string|max:128",
                "cliente.numero" => "required|string|max:8",
                "cliente.cidade" => "required|string|max:128",
                "cliente.estado" => "required|string|size:2",
                "cliente.pais" => "required|string|max:128",
                "cliente.complemento" => "nullable|string|max:128",
                "cliente.referencia" => "nullable|string|max:128",

                // Validação PF
                "pessoa_especifica.nome" => "required_if:cliente.tipo,pf|string|max:128",
                "pessoa_especifica.nascimento" => ["required_if:cliente.tipo,pf", "date_format:Y-m-d"],
                "pessoa_especifica.cpf" => [
                    "required_if:cliente.tipo,pf",
                    "string",
                    "size:11",
                    Rule::unique('pessoa_fisicas', 'cpf')->ignore($id, 'fk_clientes_id')
                ],

                // Validação PJ
                "pessoa_especifica.razao_social" => "required_if:cliente.tipo,pj|string|max:128",
                "pessoa_especifica.nome_fantasia" => "required_if:cliente.tipo,pj|string|max:128",
                "pessoa_especifica.cnpj" => [
                    "required_if:cliente.tipo,pj",
                    "size:14",
                    Rule::unique('pessoa_juridicas', 'cnpj')->ignore($id, 'fk_clientes_id')
                ],
                "pessoa_especifica.isento" => "nullable|boolean",
                "pessoa_especifica.referencia_url" => "nullable|url:http,https|max:128",
                "pessoa_especifica.inscricao_municipal" => [
                    "required_if:cliente.tipo,pj",
                    "string",
                    "max:32",
                    Rule::unique('pessoa_juridicas', 'inscricao_municipal')->ignore($id, 'fk_clientes_id')
                ],
                "pessoa_especifica.inscricao_estadual" => [
                    "required_if:cliente.tipo,pj",
                    "string",
                    "max:32",
                    Rule::unique('pessoa_juridicas', 'inscricao_estadual')->ignore($id, 'fk_clientes_id')
                ],
            ]);

            return $validated;
        } catch (ValidationException $error) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $error->errors()
            ], 422);
        }
    }

    // Função cadastro de cliente + Seu tipo de pessoa.
    public function store(Request $request)
    {
        $validated = $this->validateClient($request, null);

        if ($validated instanceof \Illuminate\Http\JsonResponse) {
            return $validated; // Retorna erro de validação
        }

        // Cria o cliente
        $cliente = Clientes::create($validated["cliente"]);

        // Cria a pessoa específica
        if ($validated["cliente"]["tipo"] === 'pf') {
            Pessoa_fisica::create(array_merge($validated["pessoa_especifica"], [
                "fk_clientes_id" => $cliente->id_cliente
            ]));
        } else {
            Pessoa_juridica::create(array_merge($validated["pessoa_especifica"], [
                "fk_clientes_id" => $cliente->id_cliente
            ]));
        }

        return response()->json(['message' => 'Cliente cadastrado com sucesso.'], 201);
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateClient($request, $id);

        if ($validated instanceof \Illuminate\Http\JsonResponse) {
            return $validated; // Retorna erro de validação
        }

        // Traz o cliente e seu tipo de pessoa específico.
        $cliente = Clientes::findOrFail($id);
        $cliente = Clientes::findOrFail($id);

        $novoTipo = $validated["cliente"]["tipo"];
        $tipoAtual = $cliente->tipo;

        // Atualiza os campos do cliente
        $cliente->update($validated["cliente"]);

        if ($novoTipo === $tipoAtual) {
            // Se o tipo não mudou, apenas atualiza a pessoa específica
            if ($tipoAtual === "pf") {
                $tipo = Pessoa_fisica::where("fk_clientes_id", "=", $id)->firstOrFail();
            } else {
                $tipo = Pessoa_juridica::where("fk_clientes_id", "=", $id)->firstOrFail();
            }
            $tipo->update($validated["pessoa_especifica"]);
        } else {
            // Se o tipo mudou, deleta o registro antigo e cria um novo
            if ($tipoAtual === "pf") {
                Pessoa_fisica::where("fk_clientes_id", "=", $id)->delete();
                $tipo = Pessoa_juridica::create(array_merge($validated["pessoa_especifica"], [
                    "fk_clientes_id" => $id
                ]));
            } else {
                Pessoa_juridica::where("fk_clientes_id", "=", $id)->delete();
                $tipo = Pessoa_fisica::create(array_merge($validated["pessoa_especifica"], [
                    "fk_clientes_id" => $id
                ]));
            }
        }

        // Junta tudo em um objeto para "responder" algo
        $resposta = (object) [
            "cliente" => $cliente,
            "pessoa_especifica" => $tipo
        ];

        return response()->json(["message" => "Cliente atualizado com sucesso", "data" => $resposta], 202);
    }

    public function destroy(string $id)
    {
        Clientes::findOrFail($id)->delete();
        return response()->json(['message' => 'Cliente deletado'], 200);
    }
}
