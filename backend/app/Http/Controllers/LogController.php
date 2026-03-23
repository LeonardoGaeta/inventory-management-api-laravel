<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = Log::orderBy("created_at", "desc");
        if ($request->has('atividade') && $request->atividade) {
            $query->where('atividade', $request->atividade);
        }
        if ($request->has('entidade') && $request->entidade) {
            $query->where('entidade', $request->entidade);
        }
        if ($request->has('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->has('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $logs = $query->paginate(15);

        $logs->getCollection()->transform(function ($log) {
            return [
                'id_log' => $log->id_log,
                'atividade' => $this->formatarAtividade($log->atividade),
                'descricao' => $log->descricao,
                'entidade' => $log->entidade,
                'entidade_id' => $log->entidade_id,
                'data' => Carbon::parse($log->created_at)->format('d/m/Y H:i:s'),
                'ip_address' => $log->ip_address,
                'dados_anteriores' => $log->dados_anteriores,
                'dados_novos' => $log->dados_novos,
                'created_at' => $log->created_at
            ];
        });

        return $logs;
    }

    public function show_last_logs()
    {
        $logs = Log::orderBy("created_at", "desc")->take(6)->get();

        return $logs->map(function ($log) {
            return [
                'ID' => $log->id_log,
                'atividade' => $this->formatarAtividade($log->atividade),
                'descricao' => $log->descricao,
                'realizadoEm' => Carbon::parse($log->created_at)->format('d/m/Y H:i')
            ];
        });
    }

    public function show($id)
    {
        $log = Log::findOrFail($id);

        return [
            'id_log' => $log->id_log,
            'atividade' => $this->formatarAtividade($log->atividade),
            'descricao' => $log->descricao,
            'entidade' => $log->entidade,
            'entidade_id' => $log->entidade_id,
            'data' => Carbon::parse($log->created_at)->format('d/m/Y H:i:s'),
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'dados_anteriores' => $log->dados_anteriores,
            'dados_novos' => $log->dados_novos,
        ];
    }

    private function formatarAtividade($atividade)
    {
        return match ($atividade) {
            'CREATE' => 'Criação',
            'UPDATE' => 'Atualização',
            'DELETE' => 'Exclusão',
            'RESTORE' => 'Restauração',
            default => $atividade
        };
    }

    public function estatisticas()
    {
        $hoje = Carbon::today();
        $semana = Carbon::now()->subWeek();
        $mes = Carbon::now()->subMonth();

        return [
            'total_logs' => Log::count(),
            'logs_hoje' => Log::whereDate('created_at', $hoje)->count(),
            'logs_semana' => Log::where('created_at', '>=', $semana)->count(),
            'logs_mes' => Log::where('created_at', '>=', $mes)->count(),
            'por_atividade' => Log::selectRaw('atividade, COUNT(*) as total')
                ->groupBy('atividade')
                ->get()
                ->pluck('total', 'atividade'),
            'por_entidade' => Log::selectRaw('entidade, COUNT(*) as total')
                ->groupBy('entidade')
                ->get()
                ->pluck('total', 'entidade')
        ];
    }
}
