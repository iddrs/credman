<form action="{{ $action }}" method="post" class="ui form">

    @include('app.partials.header', ['title' => $title])

    @csrf

    <div class="fields">
        @include('app.partials.form.field.id', ['value' => old('id', $lei->id ?? 0)])
        @include('app.partials.form.field.nr', ['value' => old('nr', $lei->nr ?? '')])
        @include('app.partials.form.field.data', ['value' => old('data', $lei->data ?? '')])
        @include('app.partials.form.field.exercicio', ['value' => old('exercicio', $lei->exercicio ?? '')])
        @include('app.partials.form.field.lei-tipo', ['value' => old('tipo', $lei->tipo ?? '')])
        @include('app.partials.form.field.valor', ['id' => 'bc_limite_exec', 'label' => 'BC Lim. Exec. (R$)', 'value' => old('bc_limite_exec', $lei->bc_limite_exec ?? 0)])
        @include('app.partials.form.field.valor', ['id' => 'bc_limite_leg', 'label' => 'BC Lim. Leg. (R$)', 'value' => old('bc_limite_leg', $lei->bc_limite_leg ?? 0)])
    </div>

    <div class="ui buttons">
        <button type="submit" class="ui positive button enter-as-tab" accesskey="ctrl+s">
            {{-- <i class="save icon"></i> --}}
            Salvar
        </button>
        @isset($cancel)
            <a class="ui basic button" href="{{ $cancel }}">
                Cancelar
            </a>
        @endisset
    </div>

</form>
