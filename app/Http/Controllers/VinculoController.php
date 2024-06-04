<?php

namespace App\Http\Controllers;

use App\Models\Credito;
use App\Models\Decreto;
use App\Models\Excesso;
use App\Models\Reducao;
use App\Models\Superavit;
use App\Models\Vinculo;
use App\Support\Helpers\Fmt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VinculoController extends Controller
{
    public function reducao($decreto_id, $credito_id)
    {
        $decreto = \App\Models\Decreto::find($decreto_id);
        $credito = \App\Models\Credito::find($credito_id);
        $reducoes_fonte = DB::select(sprintf('SELECT
        reducoes.id,
        reducoes.valor,
        rubricas.acesso,
        rubricas.uniorcam,
        rubricas.projativ,
        rubricas.despesa,
        rubricas.fonte,
        rubricas.complemento,
        SUM(vinculos.valor) AS utilizado
    FROM
        reducoes
    JOIN
        rubricas ON rubricas.id = reducoes.rubrica_id
    LEFT JOIN
        vinculos ON vinculos.reducao_id = reducoes.id
    WHERE
        reducoes.decreto_id = %d
        AND rubricas.fonte = %d
    GROUP BY
        reducoes.id, reducoes.valor, rubricas.acesso, rubricas.uniorcam, rubricas.projativ, rubricas.despesa, rubricas.fonte, rubricas.complemento
    ORDER BY rubricas.acesso ASC
    ', $decreto_id, $credito->rubrica->fonte));
        $reducoes_outras = DB::select(sprintf('SELECT
        reducoes.id,
        reducoes.valor,
        rubricas.acesso,
        rubricas.uniorcam,
        rubricas.projativ,
        rubricas.despesa,
        rubricas.fonte,
        rubricas.complemento,
        SUM(vinculos.valor) AS utilizado
    FROM
        reducoes
    JOIN
        rubricas ON rubricas.id = reducoes.rubrica_id
    LEFT JOIN
        vinculos ON vinculos.reducao_id = reducoes.id
    WHERE
        reducoes.decreto_id = %d
        AND rubricas.fonte != %d
    GROUP BY
        reducoes.id, reducoes.valor, rubricas.acesso, rubricas.uniorcam, rubricas.projativ, rubricas.despesa, rubricas.fonte, rubricas.complemento
    ORDER BY rubricas.acesso ASC
    ', $decreto_id, $credito->rubrica->fonte));
        return view('app.decreto.reducao.vincular', compact('decreto', 'credito', 'reducoes_fonte', 'reducoes_outras'));
    }

    public function storeReducao(Request $request, $decreto_id, $credito_id, $reducao_id)
    {
        $rules = [
            'valor' => 'required|decimal:0,2|min:0.01',
            'aviso' => 'nullable|string',
            'justificativa' => 'nullable|string',
            'limite' => 'boolean',
        ];

        $messages = [
            'valor.required' => 'O campo Valor e obrigatório',
            'valor.decimal' => 'O campo Valor deve ser um valor decimal',
            'valor.min' => 'O campo Valor deve ser maior ou igual a 0.01',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'decreto.credito.store');
        }

        $validated = $validator->validated();

        try {
            $decreto = Decreto::find($decreto_id);
            $record = new Vinculo($validated);
            $record->user_id = auth()->user()->id;
            $record->credito_id = $credito_id;
            $record->decreto_id = $decreto_id;
            $record->reducao_id = $reducao_id;
            $record->limite = $this->{'calcLimite'.$decreto->lei->exercicio}($record);
            $record->save();
            return redirect()->route('decreto.credito.vincular.reducao', ['decreto_id' => $decreto_id, 'credito_id' => $credito_id])->with('success', 'Redução vinculada com sucesso!');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    public function excesso($decreto_id, $credito_id)
    {
        $decreto = \App\Models\Decreto::find($decreto_id);
        $credito = \App\Models\Credito::find($credito_id);
        $excessos_fonte = DB::select(sprintf('
        SELECT
            excessos.id,
            excessos.valor,
            excessos.receita,
            excessos.fonte,
            SUM(vinculos.valor) AS utilizado
        FROM
            excessos
        LEFT JOIN
            vinculos ON vinculos.excesso_id = excessos.id
        WHERE
            excessos.decreto_id = %d
            AND excessos.fonte = %d
        GROUP BY
            excessos.id, excessos.valor, excessos.receita, excessos.fonte
        ', $decreto_id, $credito->rubrica->fonte));
        $excessos_outras = DB::select(sprintf('
            SELECT
            excessos.id,
            excessos.valor,
            excessos.receita,
            excessos.fonte,
            SUM(vinculos.valor) AS utilizado
        FROM
            excessos
        LEFT JOIN
            vinculos ON vinculos.excesso_id = excessos.id
        WHERE
            excessos.decreto_id = %d
            AND excessos.fonte != %d
        GROUP BY
            excessos.id, excessos.valor, excessos.receita, excessos.fonte
        ', $decreto_id, $credito->rubrica->fonte));
        return view('app.decreto.excesso.vincular', compact('decreto', 'credito', 'excessos_fonte', 'excessos_outras'));
    }

    public function confirmReducao(Request $request, $decreto_id, $credito_id, $reducao_id)
    {
        try {
            $decreto = Decreto::find($decreto_id);
            $credito = Credito::find($credito_id);
            $reducao = Reducao::find($reducao_id);
            $messages = [];
            if($credito->rubrica->fonte != $reducao->rubrica->fonte){
                $messages[] = sprintf('Credito no acesso %d: A redução na fonte %s está sendo vinculada a um crédito com a fonte %s.', $credito->acesso, Fmt::fonte($credito->rubrica->fonte), Fmt::fonte($reducao->rubrica->fonte));
            }

            if(($credito->rubrica->uniorcam == env('UNIORCAM_CAMARA'))
                && ($reducao->rubrica->uniorcam != env('UNIORCAM_CAMARA'))) {
                    $messages[] = 'Créditos na unidade orçamentária da Câmara com redução em unidade orçamentária que não é da Câmara.';
            }

            if(($credito->rubrica->uniorcam != env('UNIORCAM_CAMARA'))
                && ($reducao->rubrica->uniorcam == env('UNIORCAM_CAMARA'))) {
                    $messages[] = 'Créditos em unidade orçamentária que não é da Câmara com redução em unidade orçamentária da Câmara.';
            }

            if(sizeof($messages) > 0){
                return view('app.decreto.vincular.confirm', [
                    'messages' => $messages,
                    'cancel' => route('decreto.credito.vincular.reducao', compact('decreto_id', 'credito_id')),
                    'decreto' => $decreto,
                    'valor' => $request->valor,
                    'confirm' => route('decreto.credito.vincular.reducao.store', compact('decreto_id', 'credito_id', 'reducao_id')),
                ]);
            } else {
                return $this->storeReducao($request, $decreto_id, $credito_id, $reducao_id);
            }
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    public function confirmExcesso(Request $request, $decreto_id, $credito_id, $excesso_id)
    {
        try {
            $decreto = Decreto::find($decreto_id);
            $credito = Credito::find($credito_id);
            $excesso = Excesso::find($excesso_id);
            $messages = [];
            if($credito->rubrica->fonte != $excesso->fonte){
                $messages[] = sprintf('O excesso na fonte %s está sendo vinculado a um crédito com a fonte %s.', Fmt::fonte($credito->rubrica->fonte), Fmt::fonte($excesso->fonte));
            }

            if($credito->rubrica->uniorcam == env('UNIORCAM_CAMARA')) {
                    $messages[] = 'Créditos na unidade orçamentária da Câmara não deveriam ser por excesso de arrecadação';
            }

            if(sizeof($messages) > 0){
                return view('app.decreto.vincular.confirm', [
                    'messages' => $messages,
                    'cancel' => route('decreto.credito.vincular.excesso', compact('decreto_id', 'credito_id')),
                    'decreto' => $decreto,
                    'valor' => $request->valor,
                    'confirm' => route('decreto.credito.vincular.excesso.store', compact('decreto_id', 'credito_id', 'excesso_id')),
                ]);
            } else {
                return $this->storeExcesso($request, $decreto_id, $credito_id, $excesso_id);
            }
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    public function confirmSuperavit(Request $request, $decreto_id, $credito_id, $superavit_id)
    {
        try {
            $decreto = Decreto::find($decreto_id);
            $credito = Credito::find($credito_id);
            $superavit = Superavit::find($superavit_id);
            $messages = [];
            if($credito->rubrica->fonte != $superavit->fonte){
                $messages[] = sprintf('O superávit na fonte %s está sendo vinculado a um crédito com a fonte %s.', Fmt::fonte($credito->rubrica->fonte), Fmt::fonte($superavit->fonte));
            }

            if($credito->rubrica->uniorcam == env('UNIORCAM_CAMARA')) {
                    $messages[] = 'Créditos na unidade orçamentária da Câmara não deveriam ser por superávit financeiro';
            }

            if(sizeof($messages) > 0){
                return view('app.decreto.vincular.confirm', [
                    'messages' => $messages,
                    'cancel' => route('decreto.credito.vincular.superavit', compact('decreto_id', 'credito_id')),
                    'decreto' => $decreto,
                    'valor' => $request->valor,
                    'confirm' => route('decreto.credito.vincular.superavit.store', compact('decreto_id', 'credito_id', 'superavit_id')),
                ]);
            } else {
                return $this->storeSuperavit($request, $decreto_id, $credito_id, $superavit_id);
            }
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    public function storeExcesso(Request $request, $decreto_id, $credito_id, $excesso_id)
    {

        $rules = [
            'valor' => 'required|decimal:0,2|min:0.01',
            'justificativa' => 'nullable|string',
            'limite' => 'boolean',
        ];

        $messages = [
            'valor.required' => 'O campo Valor e obrigatório',
            'valor.decimal' => 'O campo Valor deve ser um valor decimal',
            'valor.min' => 'O campo Valor deve ser maior ou igual a 0.01',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'decreto.credito.store');
        }

        $validated = $validator->validated();

        try {
            $decreto = Decreto::find($decreto_id);
            $record = new Vinculo($validated);
            $record->user_id = auth()->user()->id;
            $record->credito_id = $credito_id;
            $record->decreto_id = $decreto_id;
            $record->excesso_id = $excesso_id;
            $record->limite = $this->{'calcLimite'.$decreto->lei->exercicio}($record);
            $record->save();
            return redirect()->route('decreto.credito.vincular.excesso', ['decreto_id' => $decreto_id, 'credito_id' => $credito_id])->with('success', 'Excesso vinculado com sucesso!');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    public function superavit($decreto_id, $credito_id)
    {
        $decreto = \App\Models\Decreto::find($decreto_id);
        $credito = \App\Models\Credito::find($credito_id);
        $superavits_fonte = DB::select(sprintf('
        SELECT
            superavits.id,
            superavits.valor,
            superavits.fonte,
            SUM(vinculos.valor) AS utilizado
        FROM
            superavits
        LEFT JOIN
            vinculos ON vinculos.superavit_id = superavits.id
        WHERE
            superavits.decreto_id = %d
            AND superavits.fonte = %d
        GROUP BY
            superavits.id, superavits.valor, superavits.fonte
        ', $decreto_id, $credito->rubrica->fonte));
        $superavits_outros = DB::select(sprintf('
            SELECT
            superavits.id,
            superavits.valor,
            superavits.fonte,
            SUM(vinculos.valor) AS utilizado
        FROM
            superavits
        LEFT JOIN
            vinculos ON vinculos.superavit_id = superavits.id
        WHERE
            superavits.decreto_id = %d
            AND superavits.fonte != %d
        GROUP BY
            superavits.id, superavits.valor, superavits.fonte
        ', $decreto_id, $credito->rubrica->fonte));
        return view('app.decreto.superavit.vincular', compact('decreto', 'credito', 'superavits_fonte', 'superavits_outros'));
    }

    public function storeSuperavit(Request $request, $decreto_id, $credito_id, $superavit_id)
    {
        $rules = [
            'valor' => 'required|decimal:0,2|min:0.01',
            'justificativa' => 'nullable|string',
            'limite' => 'boolean',
        ];

        $messages = [
            'valor.required' => 'O campo Valor e obrigatório',
            'valor.decimal' => 'O campo Valor deve ser um valor decimal',
            'valor.min' => 'O campo Valor deve ser maior ou igual a 0.01',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'decreto.credito.store');
        }

        $validated = $validator->validated();

        try {
            $decreto = Decreto::find($decreto_id);
            $record = new Vinculo($validated);
            $record->user_id = auth()->user()->id;
            $record->credito_id = $credito_id;
            $record->decreto_id = $decreto_id;
            $record->superavit_id = $superavit_id;
            $record->limite = $this->{'calcLimite'.$decreto->lei->exercicio}($record);
            $record->save();
            return redirect()->route('decreto.credito.vincular.superavit', ['decreto_id' => $decreto_id, 'credito_id' => $credito_id])->with('success', 'Superávit vinculado com sucesso!');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    public function destroy($decreto_id, $credito_id, $id)
    {
        try {
            $record = Vinculo::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Vínculo com id {$id} não encontrado.");
            }

            $decreto = \App\Models\Decreto::find($record->decreto_id);
            $credito = \App\Models\Credito::find($record->credito_id);

            if ($record->reducao_id) $route = 'decreto.credito.vincular.reducao';
            if ($record->excesso_id) $route = 'decreto.credito.vincular.excesso';
            if ($record->superavit_id) $route = 'decreto.credito.vincular.superavit';


            $record->delete();
            return redirect(route($route, ['decreto_id' => $decreto->id, 'credito_id' => $credito->id]))->with('success', "Vínculo com id {$id} excluído com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    protected function calcLimite2024($vinculo)
    {
        $decreto = Decreto::find($vinculo->decreto_id);
        switch ($decreto->lei->tipo) {
            case 'LOA':
                return true;
            default:
                return false;
        }
    }
}
