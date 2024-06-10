<?php

namespace App\Http\Controllers;

use App\Models\Lei;
use App\Models\Rubrica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RubricaController extends Controller
{

    protected array $rules = [
        'acesso' => ['required', 'integer',],
        'uniorcam' => ['required', 'string', 'digits:4', 'regex:/[0-9]{4}/',],
        'projativ' => ['required', 'integer', 'min:1'],
        'despesa' => ['required', 'string', 'digits:6', 'regex:/[0-9]{6}/',],
        'fonte' => ['required', 'string', 'digits:5', 'regex:/[0-9]{5}/',],
        'complemento' => ['string', 'digits:4', 'regex:/[0-9]{4}/', 'nullable'],
    ];

    protected array $messages = [
            'acesso.unique' => 'Já existe esse acesso no exercício.',
            'acesso.required' => 'Campo obrigatório.',
            'acesso.integer' => 'Apenas números são aceitos.',
            'uniorcam.required' => 'Campo obrigatório.',
            'uniorcam' => 'Formato inválido.',
            'projativ.required' => 'Campo obrigatório.',
            'projativ.integer' => 'Apenas números são aceitos.',
            'projativ.min' => 'O valor deve ser maior ou igual a 1.',
            'despesa.required' => 'Campo obrigatório.',
            'despesa' => 'Formato inválido.',
            'fonte.required' => 'Campo obrigatório.',
            'fonte' => 'Formato inválido.',
            'complemento' => 'Formato inválido.',
    ];

    public function index($exercicio = null)
    {
        if ($exercicio == null) {
            $exercicio = date('Y');
        }
        $route = 'rubricas';
        $rubricas = Rubrica::where('exercicio', $exercicio)->orderBy('acesso')->get();
        return view('app.rubrica.index', compact('rubricas', 'exercicio', 'route'));
    }

    public function create()
    {}

    public function store(Request $request, $exercicio)
    {
        $rules = $this->rules;
        $rules['acesso'][] = Rule::unique('rubricas')->ignore($request->id)->where(fn ($query) => $query->where('acesso', $request->acesso)->where('exercicio', $exercicio));

        $validator = Validator::make($request->all(), $rules, $this->messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('from', 'rubrica.store');
        }
        $validated = $validator->validated();

        try {
            $record = new Rubrica($validated);
            $record->exercicio = $exercicio;
            $record->user_id = auth()->user()->id;
            $record->save();
            $this->updateRubricas($exercicio, $validated['acesso'], $record->id);
            return redirect()->route('rubricas', $exercicio)->with('success', 'Rubrica criada com sucesso!')->with('from', 'rubrica.store');
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput()->with('from', 'rubrica.store');
        }
    }


    public function updateRubricas($exercicio, $acesso, $rubrica_id)
    {
        try {
            $leis = Lei::where('exercicio', $exercicio)->get();
            foreach ($leis as $lei) {
                $creditos = $lei->creditos->where('acesso', $acesso)->where('rubrica_id', 0);
                foreach ($creditos as $credito) {
                    $credito->update(['rubrica_id' => $rubrica_id]);
                }
                $reducoes = $lei->reducoes->where('acesso', $acesso)->where('rubrica_id', 0);
                foreach ($reducoes as $reducao) {
                    $reducao->update(['rubrica_id' => $rubrica_id]);
                }
            }
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]])->withInput()->with('from', 'rubrica.store');
        }
    }

    public function show($id)
    {}

    public function edit($id)
    {
        $rubrica = Rubrica::where('id', $id)->get()->first();
        if ($rubrica == null) {
            return back()->withErrors(['errors' => ["Rubrica com id {$id} não encontrada."]]);
        }
        return view('app.rubrica.edit', compact('rubrica'));
    }

    public function update(Request $request)
    {
        $record = Rubrica::where('id', $request->id)->get()->first();
        $rules = $this->rules;
        $rules['acesso'][] = Rule::unique('rubricas')->ignore($request->id)->where(fn ($query) => $query->where('acesso', $request->acesso)->where('exercicio', $record->exercicio));
        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            $record->update($validated);
            return redirect(route('rubricas', ['exercicio' => $record->exercicio]))->with('success', "Acesso {$record->acesso}/{$record->exercicio} ({$record->acesso}) atualizado com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

    public function delete($id)
    {}

    public function destroy($id)
    {
        try {
            $record = Rubrica::where('id', $id)->get()->first();
            if ($record == null) {
                throw new \Exception("Rubrica com id {$id} não encontrada.");
            }
            $acesso = $record->acesso;
            $exercicio = $record->exercicio;
            $record->delete();
            return redirect(route('rubricas', ['exercicio' => $exercicio]))->with('success', "Acesso {$acesso}/{$exercicio} ({$id}) excluído com sucesso!");
        } catch (\Throwable $th) {
            return back()->withErrors(['errors' => [$th->getMessage()]]);
        }
    }

}
