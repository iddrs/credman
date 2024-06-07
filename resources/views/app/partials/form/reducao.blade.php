<form action="{{ $action }}" method="post" class="ui form">

    @include('app.partials.header', ['title' => $title])

    @csrf

    <div class="fields">
        @include('app.partials.form.field.id', ['value' => old('id', $reducao->id ?? 0)])
        @include('app.partials.form.field.decreto-id', ['value' => old('decreto_id', $reducao->decreto_id ?? $decreto_id)])
        @include('app.partials.form.field.acesso', ['value' => old('acesso', $reducao->rubrica->acesso ?? '')])
        @include('app.partials.form.field.valor', ['id' => 'valor', 'label' => 'Valor', 'value' => old('valor', $reducao->valor ?? 0)])
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
