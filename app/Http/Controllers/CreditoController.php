<?php

namespace App\Http\Controllers;

use App\Models\Credito;
use App\Models\Decreto;
use App\Models\Rubrica;
use App\Models\Vinculo;
use App\Support\Helpers\Fmt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreditoController extends Controller
{

    protected array $rules = [
        'decreto_id' => ['required', 'integer', 'min:1'],
        'rubrica_id' => ['required', 'integer',],
        'acesso' => ['required', 'integer', 'min:1'],
        'valor' => ['required', 'decimal:0,2', 'min:0.01'],
        'tipo' => ['required', 'integer', 'min:1', 'max:3'],
        'origem' => ['required', 'integer', 'min:1', 'max:5'],
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
        'tipo.required' => 'Campo obrigatório.',
        'tipo.integer' => 'Apenas números são aceitos.',
        'tipo.min' => 'Opção inválida.',
        'tipo.max' => 'Opção inválida.',
        'origem.required' => 'Campo obrigatório.',
        'origem.integer' => 'Apenas números são aceitos.',
        'origem.min' => 'Opção inválida.',
        'origem.max' => 'Opção inválida.',
    ];

    public function index($decreto_id)
    {
        $decreto = Decreto::where('id', $decreto_id)->get()->first();
        return view('app.decreto.credito.index', compact('decreto'));
    }

    public function create()
    {
    }

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
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'decreto.credito.store');
        }
        $validated = $validator->validated();
        try {
            if ($this->checkIfReducaoExists($request->decreto_id, $request->acesso)) {
                throw new \Exception("Já existe redução no acesso {$request->acesso} neste decreto.");
            }
            $record = new Credito($validated);
            $record->user_id = auth()->user()->id;
            $record->save();
            return redirect()->route('decreto.creditos', ['decreto_id' => $record->decreto_id])->with('success', 'Crédito cadastrado com sucesso!')->with('from', 'decreto.credito.store');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
    }

    protected function checkIfReducaoExists($decreto_id, $acesso)
    {
        return DB::table('reducoes')->where('decreto_id', $decreto_id)->where('acesso', $acesso)->exists();
    }

    public function show($id)
    {
        // $decreto = Decreto::where('id', $id)->get()->first();
        // return view('app.decreto.show', compact('decreto'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request)
    {
    }

    public function delete($decreto_id, $id)
    {
    }

    public function destroy($decreto_id, $id)
    {
        try {
            $record = Credito::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Crédito com id {$id} não encontrado.");
            }
            $record->delete();
            $vinculacoes = Vinculo::where('credito_id', $id);
            foreach ($vinculacoes->get() as $vinculo) {
                $vinculo->delete();
            }
            return redirect(route('decreto.creditos', ['decreto_id' => $decreto_id]))->with('success', "Crédito com id {$id} excluído com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function updateRubricas($decreto_id)
    {
        try {
            $decreto = Decreto::where('id', $decreto_id)->get()->first();
            foreach ($decreto->creditos as $credito) {
                // if ($credito->rubrica_id == 0) {
                    $rubrica = Rubrica::where('exercicio', date_create_from_format('Y-m-d', $decreto->data)->format('Y'))->where('acesso', $credito->acesso)->get()->first();
                    if ($rubrica) $credito->update(['rubrica_id' => $rubrica->id]);
                // }
            }
            return redirect(route('decreto.creditos', ['decreto_id' => $decreto_id]))->with('success', "Rubricas atualizadas com sucesso.");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }
}
