<?php

namespace App\Observers;

use App\Models\Log;
use Illuminate\Database\Eloquent\Model;

class LogObserver
{
    /**
     * Handle the model "created" event.
     */
    public function created(Model $model): void
    {
        $entidade = class_basename($model);
        $entidadeId = $model->getKey();

        Log::registrar(
            atividade: 'CREATE',
            descricao: "Novo {$entidade} criado com ID {$entidadeId}",
            entidade: $entidade,
            entidadeId: $entidadeId,
            dadosAnteriores: null,
            dadosNovos: $model->toArray()
        );
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated(Model $model): void
    {
        $entidade = class_basename($model);
        $entidadeId = $model->getKey();

        $dadosAnteriores = [];
        $dadosNovos = [];

        foreach ($model->getDirty() as $campo => $novoValor) {
            $dadosAnteriores[$campo] = $model->getOriginal($campo);
            $dadosNovos[$campo] = $novoValor;
        }

        if (!empty($dadosAnteriores)) {
            Log::registrar(
                atividade: 'UPDATE',
                descricao: "Registro {$entidade} ID {$entidadeId} foi atualizado",
                entidade: $entidade,
                entidadeId: $entidadeId,
                dadosAnteriores: $dadosAnteriores,
                dadosNovos: $dadosNovos
            );
        }
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $entidade = class_basename($model);
        $entidadeId = $model->getKey();

        Log::registrar(
            atividade: 'DELETE',
            descricao: "Registro {$entidade} ID {$entidadeId} foi excluído",
            entidade: $entidade,
            entidadeId: $entidadeId,
            dadosAnteriores: $model->toArray(),
            dadosNovos: null
        );
    }

    /**
     * Handle the model "restored" event.
     */
    public function restored(Model $model): void
    {
        $entidade = class_basename($model);
        $entidadeId = $model->getKey();

        Log::registrar(
            atividade: 'RESTORE',
            descricao: "Registro {$entidade} ID {$entidadeId} foi restaurado",
            entidade: $entidade,
            entidadeId: $entidadeId,
            dadosAnteriores: null,
            dadosNovos: $model->toArray()
        );
    }
}
