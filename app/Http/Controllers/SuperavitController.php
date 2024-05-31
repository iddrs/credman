<?php

namespace App\Http\Controllers;

use App\Models\Decreto;
use App\Models\Superavit;
use App\Models\Vinculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperavitController extends Controller
{

    protected array $rules = [
        'decreto_id' => ['required', 'integer', 'min:1'],
        'fonte' => ['required', 'string', 'digits:5', 'regex:/[0-9]{5}/',],
        'valor' => ['required', 'decimal:0,2', 'min:0.01'],
    ];

    protected array $messages = [
        'decreto_id.required' => 'Campo obrigatório.',
        'decreto_id.integer' => 'Apenas números são aceitos.',
        'decreto_id.min' => 'O valor  deve ser maior que zero.',
        'fonte.required' => 'Campo obrigatório.',
        'fonte' => 'Formato inválido.',
        'valor.required' => 'Campo obrigatório.',
        'valor.decimal' => 'Apenas números são aceitos.',
        'valor.min' => 'O valor deve ser maior que zero.',
    ];

    public function index($decreto_id)
    {
        $decreto = Decreto::where('id', $decreto_id)->get()->first();
        return view('app.decreto.superavit.index', compact('decreto'));
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
            $record = new Superavit($validated);
            $record->user_id = auth()->user()->id;
            $record->save();
            return redirect()->route('decreto.superavits', ['decreto_id' => $record->decreto_id])->with('success', 'Superávit cadastrado com sucesso!')->with('from', 'decreto.superavit.store');
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
            $record = Superavit::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Superávit com id {$id} não encontrado.");
            }
            $record->delete();
            $vinculacoes = Vinculo::where('superavit_id', $id);
            foreach ($vinculacoes->get() as $vinculo) {
                $vinculo->delete();
            }
            return redirect(route('decreto.superavits', ['decreto_id' => $decreto_id]))->with('success', "Superávit com id {$id} excluído com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }
}
