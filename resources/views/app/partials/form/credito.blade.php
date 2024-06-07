<form action="{{ $action }}" method="post" class="ui form">

    @include('app.partials.header', ['title' => $title])

    @csrf

    <div class="fields">
        @include('app.partials.form.field.id', ['value' => old('id', $credito->id ?? 0)])
        @include('app.partials.form.field.decreto-id', ['value' => old('decreto_id', $credito->decreto_id ?? $decreto_id)])
        @include('app.partials.form.field.acesso', ['value' => old('acesso', $credito->rubrica->acesso ?? '')])
        @include('app.partials.form.field.valor', ['id' => 'valor', 'label' => 'Valor', 'value' => old('valor', $credito->valor ?? 0)])
        @include('app.partials.form.field.credito-tipo', ['value' => old('tipo', $credito->tipo ?? '')])
        @include('app.partials.form.field.origem-tipo', ['value' => old('origem', $credito->origem ?? '')])
    </div>

    <div class="ui buttons">
        <button type="submit" class="ui positive button enter-as-tab" accesskey="ctrl+s">
            Salvar
        </button>
        @isset($cancel)
            <a class="ui basic button" href="{{ $cancel }}">
                Cancelar
            </a>
        @endisset
    </div>

</form>
