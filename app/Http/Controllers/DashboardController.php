<?php

namespace App\Http\Controllers;

use App\Models\Lei;
use App\Support\Enums\TiposLei;
use App\Support\Helpers\Fmt;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index($exercicio = null)
    {
        if ($exercicio == null) {
            $exercicio = date('Y');
        }
        $route = 'dashboard';
        $totalCredito = $this->totalCredito($exercicio);
        $totalSuplementar = $this->totalPorTipo($exercicio, 1);
        $totalEspecial = $this->totalPorTipo($exercicio, 2);
        $totalExtraordinario = $this->totalPorTipo($exercicio, 3);
        $totalLimiteSuplementacao = $this->totalLimiteSuplementacao($exercicio);
        $totalReducao = $this->totalPorOrigem($exercicio, 1);
        $totalSuperavit = $this->totalPorOrigem($exercicio, 2);
        $totalExcesso = $this->totalPorOrigem($exercicio, 3);
        $totalReabertura = $this->totalPorOrigem($exercicio, 4);
        $ultimosDecretos = $decretos = DB::table('decretos')->join('leis', 'decretos.lei_id', '=', 'leis.id')->where('leis.exercicio', $exercicio)->select('decretos.*', 'leis.nr as lei')->orderBy('nr', 'desc')->limit(5)->get();
        $ultimasLeis = DB::table('leis')->where('exercicio', $exercicio)->orderBy('id', 'desc')->limit(5)->get();

        return view('app.index', compact('exercicio', 'route', 'totalCredito', 'totalSuplementar', 'totalEspecial', 'totalExtraordinario', 'totalLimiteSuplementacao', 'totalReducao', 'totalSuperavit', 'totalExcesso', 'totalReabertura', 'ultimosDecretos', 'ultimasLeis'));
    }

    public function totalCredito($exercicio)
    {
        $total = 0.0;
        foreach(Lei::where('exercicio', $exercicio)->get() as $lei) {
            $total += $lei->creditos->sum('valor');
        }
        return $total;

    }

    public function totalPorTipo($exercicio, $tipo)
    {
        $total = 0.0;
        foreach(Lei::where('exercicio', $exercicio)->get() as $lei) {
            $total += $lei->creditos->where('tipo', $tipo)->sum('valor');
        }
        return $total;

    }

    public function totalPorOrigem($exercicio, $origem)
    {
        $total = 0.0;
        foreach(Lei::where('exercicio', $exercicio)->get() as $lei) {
            $total += $lei->creditos->where('origem', $origem)->sum('valor');
        }
        return $total;

    }

    public function totalLimiteSuplementacao($exercicio)
    {
        $loa = Lei::where('exercicio', $exercicio)->where('tipo', TiposLei::LOA)->first();
        $decreto = $loa?->decretos?->last();
        if($decreto == null) {
            return 0.0;
        }
        return LeiController::calcLimiteAteDecreto($decreto->id) / $loa->bc_limite;
    }
}
