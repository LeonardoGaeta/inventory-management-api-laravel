<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    protected $primaryKey = "id_log";
    const UPDATED_AT = null;

    protected $fillable = [
        "atividade",
        "descricao",
        "entidade",
        "entidade_id",
        "dados_anteriores",
        "dados_novos",
        "ip_address",
        "user_agent"
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
    ];

    public static function registrar($atividade, $descricao, $entidade = null, $entidadeId = null, $dadosAnteriores = null, $dadosNovos = null)
    {
        return self::create([
            'atividade' => $atividade,
            'descricao' => $descricao,
            'entidade' => $entidade,
            'entidade_id' => $entidadeId,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
