<form action="{{ $action }}" method="post" class="ui form">

    @include('app.partials.header', ['title' => $title])

    @csrf

    <div class="fields">
        @include('app.partials.form.field.id', ['value' => old('id', $superavit->id ?? 0)])
        @include('app.partials.form.field.decreto-id', ['value' => old('decreto_id', $superavit->decreto_id ?? $decreto_id)])
        @include('app.partials.form.field.valor', ['id' => 'valor', 'label' => 'Valor', 'value' => old('valor', $superavit->valor ?? '')])
        @include('app.partials.form.field.fonte', ['value' => old('fonte', $superavit->fonte ?? '')])
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
