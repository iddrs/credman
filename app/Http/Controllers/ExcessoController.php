<?php

namespace App\Http\Controllers;

use App\Models\Decreto;
use App\Models\Excesso;
use App\Models\Rubrica;
use App\Models\Vinculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExcessoController extends Controller
{

    protected array $rules = [
        'decreto_id' => ['required', 'integer', 'min:1'],
        'receita' => ['required', 'string', 'digits:14', 'regex:/[0-9]{14}/',],
        'fonte' => ['required', 'string', 'digits:5', 'regex:/[0-9]{5}/',],
        'valor' => ['required', 'decimal:0,2', 'min:0.01'],
    ];

    protected array $messages = [
        'decreto_id.required' => 'Campo obrigatório.',
        'decreto_id.integer' => 'Apenas números são aceitos.',
        'decreto_id.min' => 'O valor  deve ser maior que zero.',
        'receita.required' => 'Campo obrigatório.',
        'receita' => 'Formato inválido.',
        'fonte.required' => 'Campo obrigatório.',
        'fonte' => 'Formato inválido.',
        'valor.required' => 'Campo obrigatório.',
        'valor.decimal' => 'Apenas números são aceitos.',
        'valor.min' => 'O valor deve ser maior que zero.',
    ];

    public function index($decreto_id)
    {
        $decreto = Decreto::where('id', $decreto_id)->get()->first();
        return view('app.decreto.excesso.index', compact('decreto'));
    }

    public function create()
    {}

    public function store(Request $request)
    {
        $rules = $this->rules;
        $validator = Validator::make($request->all(), $rules, $this->messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'decreto.excesso.store');
        }
        $validated = $validator->validated();
        try {
            $record = new Excesso($validated);
            $record->user_id = auth()->user()->id;
            $record->save();
            return redirect()->route('decreto.excessos', ['decreto_id' => $record->decreto_id])->with('success', 'Excesso cadastrado com sucesso!')->with('from', 'decreto.excesso.store');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
        }
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
            $record = Excesso::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Excesso com id {$id} não encontrado.");
            }
            $record->delete();
            $vinculacoes = Vinculo::where('excesso_id', $id);
            foreach ($vinculacoes->get() as $vinculo) {
                $vinculo->delete();
            }
            return redirect(route('decreto.excessos', ['decreto_id' => $decreto_id]))->with('success', "Excesso com id {$id} excluído com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }
}
