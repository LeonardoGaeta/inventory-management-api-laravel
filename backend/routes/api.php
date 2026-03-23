<?php

use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProdutosPedidoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\PessoaFisicaController;
use App\Http\Controllers\PessoaJuridicaController;
use App\Http\Controllers\LogController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Produtos
Route::apiResource('produtos', ProdutosController::class);

// Pedidos
Route::apiResource('pedidos', PedidosController::class);

// Produtos Pedido
Route::get('produtos-pedido/{id}', [ProdutosPedidoController::class, 'show_produtos_pedido']);
Route::post('produtos-pedido', [ProdutosPedidoController::class, 'store']);
Route::put('produtos-pedido/{id}', [ProdutosPedidoController::class, 'update']);
Route::delete('produtos-pedido/{id}', [ProdutosPedidoController::class, 'destroy']);

// Clientes
Route::post('clientes', [ClientesController::class, 'store']);
Route::put('clientes/{id}', [ClientesController::class, 'update']);
Route::delete('clientes/{id}', [ClientesController::class, 'destroy']);

// // Pessoa Fisica
Route::get('pessoa-fisica', [PessoaFisicaController::class, 'index']);
Route::get('pessoa-fisica/{id}', [PessoaFisicaController::class, 'show']);

// // Pessoa Juridica
Route::get('pessoa-juridica', [PessoaJuridicaController::class, 'index']);
Route::get('pessoa-juridica/{id}', [PessoaJuridicaController::class, 'show']);


// Logs
Route::get('logs', [LogController::class, 'index']);
Route::get('logs/recent', [LogController::class, 'show_last_logs']);
Route::get('logs/statistics', [LogController::class, 'estatisticas']);
Route::get('logs/{id}', [LogController::class, 'show']);
