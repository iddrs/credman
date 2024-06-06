<form action="{{ $action }}" method="post" class="ui form">

    @include('app.partials.header', ['title' => $title])

    @csrf

    <div class="fields">
        @include('app.partials.form.field.id', ['value' => old('id', $excesso->id ?? 0)])
        @include('app.partials.form.field.decreto-id', ['value' => old('decreto_id', $excesso->decreto_id ?? $decreto_id)])
        @include('app.partials.form.field.receita', ['value' => old('receita', $excesso->receita ?? '')])
        @include('app.partials.form.field.fonte', ['value' => old('fonte', $excesso->fonte ?? '')])
        @include('app.partials.form.field.valor', ['id' => 'valor', 'label' => 'Valor', 'value' => old('valor', $excesso->valor ?? 0)])
    </div>

    <div class="ui buttons">
        <button type="submit" class="ui positive button enter-as-tab" >
            <i class="save icon"></i>
            Salvar
        </button>
        @isset($cancel)
            <a class="ui basic button" href="{{ $cancel }}">
                Cancelar
            </a>
        @endisset
    </div>

</form>
