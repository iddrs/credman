<?php

namespace App\Http\Controllers;

use App\Models\Decreto;
use App\Models\Lei;
use Illuminate\Http\Request;
use App\Support\Enums\TiposLei;
use App\Support\Helpers\Fmt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeiController extends Controller
{

    protected array $rules = [
        'nr' => ['required', 'integer',],
        'data' => ['required', 'date', 'date_format:Y-m-d'],
        'exercicio' => ['required', 'integer', 'min:1', 'digits:4'],
        'bc_limite_exec' => ['required', 'decimal:0,2', 'min:0'],
        'bc_limite_leg' => ['required', 'decimal:0,2', 'min:0'],
    ];

    protected array $messages = [
            'nr.unique' => 'Já existe essa lei.',
            'nr.required' => 'Campo obrigatório.',
            'nr.integer' => 'Apenas números são aceitos.',
            'data.required' => 'Campo obrigatório.',
            'data.date' => 'Data inválida.',
            'data.date_format' => 'Formato inválido.',
            'exercicio.required' => 'Campo obrigatório.',
            'exercicio.integer' => 'Apenas números são aceitos.',
            'exercicio.min' => 'O valor deve ser maior ou igual a 1.',
            'exercicio.digits' => 'O exer´cio deve ter 4 dígitos.',
            'tipo.required' => 'Campo obrigatório.',
            'tipo.enum' => 'Opção inválida.',
            'bc_limite_exec.required' => 'Campo obrigatório.',
            'bc_limite_exec.decimal' => 'Apenas números são aceitos.',
            'bc_limite_exec.min' => 'O valor deve ser positivo.',
            'bc_limite_leg.required' => 'Campo obrigatório.',
            'bc_limite_leg.decimal' => 'Apenas números são aceitos.',
            'bc_limite_leg.min' => 'O valor deve ser positivo.',
    ];

    public function index()
    {
        $leis = Lei::orderBy('nr', 'desc')->get();
        return view('app.lei.index', compact('leis'));
    }

    public function create()
    {}

    public function store(Request $request)
    {
        $rules = $this->rules;
        $rules['tipo'][] = Rule::enum(TiposLei::class);
        $rules['nr'][] = 'unique:leis,nr';
        $validator = Validator::make($request->all(), $rules, $this->messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'lei.store');
        }
        $validated = $validator->validated();

        try {
            $record = new Lei($validated);
            $record->user_id = auth()->user()->id;
            $record->save();
            // return redirect()->route('leis')->with('success', 'Lei cadastrada com sucesso!')->with('from', 'lei.store');
            return redirect()->route('lei.show', ['id' => $record->id])->with('success', 'Lei cadastrada com sucesso!');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function show($id)
    {
        $lei = Lei::where('id', $id)->get()->first();
        $decretos = $lei->decretos;
        return view('app.lei.show', compact('lei', 'decretos'));
    }

    public function edit($id)
    {
        $lei = Lei::where('id', $id)->get()->first();
        return view('app.lei.edit', compact('lei'));
    }

    public function update(Request $request)
    {
        $record = Lei::where('id', $request->id)->get()->first();
        $rules = $this->rules;
        $rules['tipo'][] = Rule::enum(TiposLei::class);
        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            $record->update($validated);
            return redirect(route('lei.show', ['id' => $record->id]))->with('success', "Lei nº {$record->nr} ({$record->id}) atualizada com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function delete($id)
    {
        $lei = Lei::where('id', $id)->get()->first();
        return view('app.lei.delete', compact('lei'));
    }

    public function destroy($id)
    {
        try {
            $record = Lei::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Lei com id {$id} não encontrada.");
            }
            $lei = Fmt::docnumber($record->nr);
            $record->delete();
            return redirect(route('leis'))->with('success', "Lei nº {$lei} ({$id}) excluída com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public static function calcLimiteAteDecreto($decreto_id, $tipo_decreto)
    {
        $decreto = Decreto::where('id', $decreto_id)->get()->first();
        $data_limite = $decreto->data;
        $lei = $decreto->lei->id;
        $valor = DB::select("select sum(vinculos.valor) as valor from vinculos where vinculos.limite = 1 and decreto_id in (select id from decretos where lei_id = {$lei} and data <= '{$data_limite}' and tipo_decreto = {$tipo_decreto})");
        $val = $valor[0]->valor;
        switch ($decreto->tipo_decreto) {
            case 'D';
                return $val / $decreto->bc_limite_exec;
                break;
            case 'M';
            return $val / $decreto->bc_limite_leg;
                break;
            default:
                throw new \Exception('Tipo de decreto inválido');
                break;
        }

    }

}
