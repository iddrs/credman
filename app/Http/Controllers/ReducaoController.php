<?php

namespace App\Http\Controllers;

use App\Models\Decreto;
use App\Models\Reducao;
use App\Models\Rubrica;
use App\Models\Vinculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReducaoController extends Controller
{

    protected array $rules = [
        'decreto_id' => ['required', 'integer', 'min:1'],
        'rubrica_id' => ['required', 'integer',],
        'acesso' => ['required', 'integer', 'min:1'],
        'valor' => ['required', 'decimal:0,2', 'min:0.01'],
    ];

    protected array $messages = [
        'decreto_id.required' => 'Campo obrigatório.',
        'decreto_id.integer' => 'Apenas números são aceitos.',
        'decreto_id.min' => 'O valor  deve ser maior que zero.',
        'rubrica_id.required' => 'Campo obrigatório.',
        'rubrica_id.integer' => 'Apenas números são aceitos.',
        'acesso.required' => 'Campo obrigatório.',
        'acesso.integer' => 'Apenas números são aceitos.',
        'acesso.min' => 'O valor  deve ser maior que zero.',
        'valor.required' => 'Campo obrigatório.',
        'valor.decimal' => 'Apenas números são aceitos.',
        'valor.min' => 'O valor deve ser maior que zero.',
    ];

    public function index($decreto_id)
    {
        $decreto = Decreto::where('id', $decreto_id)->get()->first();
        return view('app.decreto.reducao.index', compact('decreto'));
    }

    public function create()
    {}

    protected function rubrica_id($decreto_id, $acesso)
    {
        $decreto = Decreto::where('id', $decreto_id)->get()->first();
        $exercicio = date_create_from_format('Y-m-d', $decreto->data)->format('Y');
        $rubrica = Rubrica::where('exercicio', $exercicio)->where('acesso', $acesso)->get()->first();
        if (!$rubrica) return 0;
        return $rubrica->id;
    }

    public function store(Request $request)
    {
        $request->merge(['rubrica_id' => $this->rubrica_id($request->decreto_id, $request->acesso)]);
        $rules = $this->rules;
        $validator = Validator::make($request->all(), $rules, $this->messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'decreto.reducao.store');
        }
        $validated = $validator->validated();
        try {
            if ($this->checkIfCreditoExists($request->decreto_id, $request->acesso)) {
                throw new \Exception("Já existe crédito no acesso {$request->acesso} neste decreto.");
            }
            $record = new Reducao($validated);
            $record->user_id = auth()->user()->id;
            $record->save();
            return redirect()->route('decreto.reducoes', ['decreto_id' => $record->decreto_id])->with('success', 'Redução cadastrada com sucesso!')->with('from', 'decreto.reducao.store');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    protected function checkIfCreditoExists($decreto_id, $acesso)
    {
        return DB::table('creditos')->where('decreto_id', $decreto_id)->where('acesso', $acesso)->exists();
    }

    public function show($id)
    {}

    public function edit($id)
    {}

    public function update(Request $request)
    {}

    public function delete($decreto_id, $id)
    {}

    public function destroy($decreto_id, $id)
    {
        try {
            $record = Reducao::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Redução com id {$id} não encontrada.");
            }
            $record->delete();
            $vinculacoes = Vinculo::where('reducao_id', $id);
            foreach ($vinculacoes->get() as $vinculo) {
                $vinculo->delete();
            }
            return redirect(route('decreto.reducoes', ['decreto_id' => $decreto_id]))->with('success', "Redução com id {$id} excluída com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function updateRubricas($decreto_id)
    {
        try {
            $decreto = Decreto::where('id', $decreto_id)->get()->first();
            foreach ($decreto->reducoes as $reducao) {
                // if ($reducao->rubrica_id == 0) {
                    $rubrica = Rubrica::where('exercicio', date_create_from_format('Y-m-d', $decreto->data)->format('Y'))->where('acesso', $reducao->acesso)->get()->first();
                    if ($rubrica) $reducao->update(['rubrica_id' => $rubrica->id]);
                // }
            }
            return redirect(route('decreto.reducoes', ['decreto_id' => $decreto_id]))->with('success', "Rubricas atualizadas com sucesso.");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }
}
