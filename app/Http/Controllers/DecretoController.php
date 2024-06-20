<?php

namespace App\Http\Controllers;

use App\Models\Decreto;
use App\Models\Rubrica;
use App\Support\Helpers\Fmt;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\TemplateProcessor;

class DecretoController extends Controller
{
    protected array $rules = [
        'lei_id' => ['required', 'integer', 'min:1'],
        'tipo_decreto' => ['required', 'in:D,M',],
        'nr' => ['required', 'integer',],
        'data' => ['required', 'date', 'date_format:Y-m-d'],
        'vl_credito' => ['required', 'decimal:0,2', 'min:0'],
        'vl_reducao' => ['required', 'decimal:0,2', 'min:0'],
        'vl_superavit' => ['required', 'decimal:0,2', 'min:0'],
        'vl_excesso' => ['required', 'decimal:0,2', 'min:0'],
        'vl_reaberto' => ['required', 'decimal:0,2', 'min:0'],
    ];

    protected array $messages = [
        'nr.unique' => 'Já existe decreto com este tipo neste número e nesta data.',
        'tipo_decreto.required' => 'Campo obrigatório.',
        'tipo_decreto.in' => 'Valor inválido.',
        'nr.required' => 'Campo obrigatório.',
        'nr.integer' => 'Apenas números são aceitos.',
        'data.required' => 'Campo obrigatório.',
        'data.date' => 'Data inválida.',
        'data.date_format' => 'Formato inválido.',
        'vl_credito.required' => 'Campo obrigatório.',
        'vl_credito.decimal' => 'Apenas números são aceitos.',
        'vl_credito.min' => 'O valor deve ser positivo.',
        'vl_reducao.required' => 'Campo obrigatório.',
        'vl_reducao.decimal' => 'Apenas números são aceitos.',
        'vl_reducao.min' => 'O valor deve ser positivo.',
        'vl_superavit.required' => 'Campo obrigatório.',
        'vl_superavit.decimal' => 'Apenas números são aceitos.',
        'vl_superavit.min' => 'O valor deve ser positivo.',
        'vl_excesso.required' => 'Campo obrigatório.',
        'vl_excesso.decimal' => 'Apenas números são aceitos.',
        'vl_excesso.min' => 'O valor deve ser positivo.',
        'vl_reaberto.required' => 'Campo obrigatório.',
        'vl_reaberto.decimal' => 'Apenas números são aceitos.',
        'vl_reaberto.min' => 'O valor deve ser positivo.',
    ];

    public function index($exercicio = null)
    {
        if ($exercicio == null) {
            $exercicio = date('Y');
        }
        $route = 'decretos';
        $decretos = DB::table('decretos')->join('leis', 'decretos.lei_id', '=', 'leis.id')->where('leis.exercicio', $exercicio)->select('decretos.*', 'leis.nr as lei')->orderBy('nr', 'desc')->get();
        return view('app.decreto.index', compact('decretos', 'exercicio', 'route'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        // $rules['nr'][] = 'unique:decretos,nr,data';
        $rules['nr'][] = Rule::unique('decretos')->where(fn (Builder $query) => $query->where('tipo_decreto', $request->get('tipo_decreto'))->where('nr', $request->get('nr'))->where('data', $request->get('data')));
        $validator = Validator::make($request->all(), $rules, $this->messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'decreto.store');
        }
        $validated = $validator->validated();
        try {
            $record = new Decreto($validated);
            $record->user_id = auth()->user()->id;
            $record->save();
            return redirect()->route('decreto.show', ['id' => $record->id])->with('success', 'Decreto cadastrado com sucesso!')->with('from', 'decreto.store');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput()->with('from', 'decreto.store');
        }
    }

    public function show($id)
    {
        $decreto = Decreto::where('id', $id)->get()->first();
        return view('app.decreto.show', compact('decreto'));
    }

    public function edit($id)
    {
        $decreto = Decreto::where('id', $id)->get()->first();
        return view('app.decreto.edit', compact('decreto'));
    }

    public function update(Request $request)
    {
        $record = Decreto::where('id', $request->id)->get()->first();
        $rules = $this->rules;
        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        try {
            $record->update($validated);
            $tipo = \App\Support\Enums\TiposDecreto::getLabel($record->tipo_decreto);
            return redirect(route('decreto.show', ['id' => $record->id]))->with('success', "$tipo nº {$record->nr} ({$record->id}) atualizado com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function delete($id)
    {
        $decreto = Decreto::where('id', $id)->get()->first();
        return view('app.decreto.delete', compact('decreto'));
    }

    public function destroy($id)
    {
        try {
            $record = Decreto::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Decreto com id {$id} não encontrada.");
            }
            $nr = Fmt::docnumber($record->nr);
            $lei = $record->lei->id;
            $tipo = \App\Support\Enums\TiposDecreto::getLabel($record->tipo_decreto);
            $record->delete();
            return redirect(route('lei.show', ['id' => $lei]))->with('success', "$tipo nº {$nr} ({$id}) excluído com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function verify($id)
    {
        $decreto = Decreto::where('id', $id)->get()->first();


        $test_list = [
            'testTotalCreditoLancado',
            'testTotalCreditoPorReducaoLancada',
            'testTotalCreditoPorExcessoLancado',
            'testTotalCreditoPorSuperavitLancado',
            'testTotalCreditoVinculado',
            'testTotalReducaoVinculada',
            'testTotalExcessoVinculado',
            'testTotalSuperavitVinculado',
        ];

        $result = [];
        $verified = true;
        foreach ($test_list as $test) {
            $r = $this->$test($decreto->id);
            $name = $r['name'];
            $val1 = $r['val1'];
            $val2 = $r['val2'];
            $diff = round($val1 - $val2, 2);
            $success = ($diff == 0) ? true : false;
            $result[$test] = [
                'name' => $name,
                'val1' => $val1,
                'val2' => $val2,
                'diff' => $diff,
                'success' => $success
            ];
            if (!$success) $verified = false;
        }

        $warnings = DB::table('vinculos')->where('decreto_id', $decreto->id)->whereNotNull('aviso')->get();
        // dd($warnings);

        return view('app.decreto.verify', compact('decreto', 'verified', 'result', 'warnings'));
    }

    public function testTotalCreditoLancado($decreto_id)
    {
        $name = 'Créditos do decreto = Créditos lançados';
        $val1 = DB::table('decretos')->where('id', $decreto_id)->sum('vl_credito');
        $val2 = DB::table('creditos')->where('decreto_id', $decreto_id)->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function testTotalCreditoPorReducaoLancada($decreto_id)
    {
        $name = 'Créditos do decreto = Créditos lançados por redução';
        $val1 = DB::table('decretos')->where('id', $decreto_id)->sum('vl_reducao');
        $val2 = DB::table('reducoes')->where('decreto_id', $decreto_id)->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function testTotalCreditoPorExcessoLancado($decreto_id)
    {
        $name = 'Créditos do decreto = Créditos lançados por excesso';
        $val1 = DB::table('decretos')->where('id', $decreto_id)->sum('vl_excesso');
        $val2 = DB::table('excessos')->where('decreto_id', $decreto_id)->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function testTotalCreditoPorSuperavitLancado($decreto_id)
    {
        $name = 'Créditos do decreto = Créditos lançados por superávit';
        $val1 = DB::table('decretos')->where('id', $decreto_id)->sum('vl_superavit');
        $val2 = DB::table('superavits')->where('decreto_id', $decreto_id)->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function testTotalCreditoVinculado($decreto_id)
    {
        $name = 'Total do decreto = Créditos vinculados';
        $val1 = round(DB::table('decretos')->where('id', $decreto_id)->sum('vl_credito') - DB::table('decretos')->where('id', $decreto_id)->sum('vl_reaberto'), 2);
        $val2 = DB::table('vinculos')->where('decreto_id', $decreto_id)->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function testTotalReducaoVinculada($decreto_id)
    {
        $name = 'Redução do decreto = Créditos vinculados por redução';
        $val1 = DB::table('decretos')->where('id', $decreto_id)->sum('vl_reducao');
        $val2 = DB::table('vinculos')->where('decreto_id', $decreto_id)->whereNotNull('reducao_id')->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function testTotalExcessoVinculado($decreto_id)
    {
        $name = 'Excesso do decreto = Créditos vinculados por excesso';
        $val1 = DB::table('decretos')->where('id', $decreto_id)->sum('vl_excesso');
        $val2 = DB::table('excessos')->where('decreto_id', $decreto_id)->whereNotNull('excesso_id')->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function testTotalSuperavitVinculado($decreto_id)
    {
        $name = 'Superávit do decreto = Créditos vinculados por superávit';
        $val1 = DB::table('decretos')->where('id', $decreto_id)->sum('vl_superavit');
        $val2 = DB::table('superavits')->where('decreto_id', $decreto_id)->whereNotNull('superavit_id')->sum('valor');
        return compact('name', 'val1', 'val2');
    }

    public function close($id)
    {
        $decreto = Decreto::where('id', $id)->get()->first();
        $decreto->fechado = true;
        $decreto->save();
        return redirect()->route('decreto.show', ['id' => $decreto->id]);
    }

    public function open($id)
    {
        $decreto = Decreto::where('id', $id)->get()->first();
        $decreto->fechado = false;
        $decreto->save();
        return redirect()->route('decreto.show', ['id' => $decreto->id]);
    }

    public function docx($id)
    {
        try {
            $decreto = Decreto::where('id', $id)->get()->first();
            switch($decreto->tipo_decreto) {
                case 'D':
                    $modelo = 'templates/decreto.docx';
                    $fileName = 'decretos/DECRETO Nº %03d-%d credito adicional L%d.docx';
                    break;
                case 'M':
                    $modelo = 'templates/resolucao-mesa.docx';
                    $fileName = 'decretos/RESOLUÇÃO DE MESA Nº %03d-%d credito adicional L%d.docx';
                    break;
                default:
                    throw new \Exception("Tipo de decreto inválido: {$decreto->tipo_decreto}");
            }
            $templateProcessor = new TemplateProcessor(public_path($modelo));

            $templateProcessor->setValue('nrDecreto', Fmt::docnumber($decreto->nr));
            $templateProcessor->setValue('dataDecreto', Fmt::dataPorExtenso($decreto->data));
            $templateProcessor->setValue('nrLei', Fmt::docnumber($decreto->lei->nr));
            $templateProcessor->setValue('dataLei', Fmt::dataPorExtenso($decreto->lei->data));
            $templateProcessor->setValue('exercicio', $decreto->lei->exercicio);

            $replacements = [];
            $art = 0;
            foreach (DB::table('creditos')->selectRaw('tipo, origem, sum(valor) as total')->where('decreto_id', $decreto->id)->groupBy('tipo', 'origem')->get() as $credito) {
                $art++;
                $totalTipoCredito = Fmt::money($credito->total);
                switch ($credito->tipo) {
                    case 1:
                        $tipoCredito = 'suplementar';
                        break;
                    case 2:
                        $tipoCredito = 'especial';
                        break;
                    case 3:
                        $tipoCredito = 'extraordinário';
                        break;
                }
                switch ($credito->origem) {
                    case 1:
                        $tipoOrigem = 'a redução de dotações orçamentárias';
                        break;
                    case 2:
                        $tipoOrigem = 'o superávit financeiro do exercício anterior';
                        break;
                    case 3:
                        $tipoOrigem = 'o excesso de arrecadação';
                        break;
                    case 4:
                        $tipoOrigem = 'a reabertura de créditos abertos nos últimos quatro meses do exercício anterior';
                        break;
                }
                $replacements[] = [
                    'artigo' => $art,
                    'tipoCredito' => $tipoCredito,
                    'totalTipoCredito' => $totalTipoCredito,
                    'totalOrigem' => $totalTipoCredito,
                    'tipoOrigem' => $tipoOrigem,
                ];
            }

            $templateProcessor->cloneBlock('abertura', 0, true, false, $replacements);

            if ($decreto->lei->tipo == 'LOA') {
                $art++;
                $templateProcessor->cloneBlock('limite', 1, true, true);
                $templateProcessor->setValue('artigo#1', $art);
                $templateProcessor->setValue('percentualLimite#1', number_format(LeiController::calcLimiteAteDecreto($decreto->id, $decreto->tipo_decreto) * 100, 2, ',', '.'));
            }
            $templateProcessor->cloneBlock('limite', 0, true, true);

            $art++;
            $templateProcessor->setValue('artigo', $art);

            $replacements = [];
            foreach ($decreto->vinculos as $k =>$vinculo) {

                $creditoValor = Fmt::money($vinculo->valor);
                $credito = $vinculo->credito;
                switch ($credito->tipo) {
                    case 1:
                        $creditoTipo = 'Crédito Suplementar';
                        break;
                    case 2:
                        $creditoTipo = 'Crédito Especial';
                        break;
                    case 3:
                        $creditoTipo = 'Crédito Extraordinário';
                        break;
                }
                switch ($credito->origem) {
                    case 1:
                        $origemTipo = 'redução da dotação orçamentária';
                        break;
                    case 2:
                        $origemTipo = 'superávit financeiro na fonte de recursos';
                        break;
                    case 3:
                        $origemTipo = 'excesso de arrecadação na receita';
                        break;
                    case 4:
                        $origemTipo = 'reabertura de créditos do exercício anterior';
                        break;
                }


                $creditoRubrica = sprintf('(%04d) %s %s %s %s %s', $credito->rubrica->acesso, Fmt::uniorcam($credito->rubrica->uniorcam), Fmt::projativ($credito->rubrica->projativ), Fmt::despesa($credito->rubrica->despesa), Fmt::fonte($credito->rubrica->fonte), is_null($credito->rubrica->complemento)?'':($credito->rubrica->complemento));


                $origemCodificacao = '';
                if(!is_null($vinculo->reducao_id)){
                    $origemCodificacao = sprintf('(%04d) %s %s %s %s %s', $vinculo->reducao->acesso, Fmt::uniorcam($vinculo->reducao->rubrica->uniorcam), Fmt::projativ($vinculo->reducao->rubrica->projativ), Fmt::despesa($vinculo->reducao->rubrica->despesa), Fmt::fonte($vinculo->reducao->rubrica->fonte), is_null($vinculo->reducao->rubrica->complemento)?'':($vinculo->reducao->rubrica->complemento));
                }
                if(!is_null($vinculo->superavit_id)){
                    $origemCodificacao = sprintf('%s', Fmt::fonte($vinculo->superavit->fonte));
                }
                if(!is_null($vinculo->excesso_id)){
                    $origemCodificacao = sprintf('%s %s', Fmt::receita($vinculo->excesso->receita), Fmt::fonte($vinculo->excesso->fonte));
                }

                if(($k + 1) === sizeof($decreto->vinculos)) {
                    $final = '.';
                } else {
                    $final = ';';
                }
                $origemCodificacao .= $final;

                $replacements[] = [
                    'creditoTipo' => $creditoTipo,
                    'creditoRubrica' => $creditoRubrica,
                    'origemTipo' => $origemTipo,
                    'origemCodificacao' => $origemCodificacao,
                    'creditoValor' => $creditoValor,
                ];
            }
            $templateProcessor->cloneBlock('itemAnexo', 0, true, false, $replacements);

            $outputFileName = sprintf($fileName, $decreto->nr, $decreto->lei->exercicio, $decreto->lei->nr);
            $templateProcessor->saveAs(storage_path($outputFileName));
            return response()->download(storage_path($outputFileName));
        } catch (\Exception $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function updateRubricas($id)
    {
        try {
            $decreto = Decreto::where('id', $id)->get()->first();
            foreach ($decreto->creditos as $credito) {
                    $rubrica = Rubrica::where('exercicio', date_create_from_format('Y-m-d', $decreto->data)->format('Y'))->where('acesso', $credito->acesso)->get()->first();
                    if ($rubrica) $credito->update(['rubrica_id' => $rubrica->id]);
            }
            foreach ($decreto->reducoes as $reducao) {
                    $rubrica = Rubrica::where('exercicio', date_create_from_format('Y-m-d', $decreto->data)->format('Y'))->where('acesso', $reducao->acesso)->get()->first();
                    if ($rubrica) $reducao->update(['rubrica_id' => $rubrica->id]);
            }
            return redirect(route('decreto.show', ['id' => $id]))->with('success', "Rubricas atualizadas com sucesso.");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

}
