<form action="{{ $action }}" method="post" class="ui form">

    @include('app.partials.header', ['title' => $title])

    @csrf

    <div class="fields">
        @include('app.partials.form.field.id', ['value' => old('id', $decreto->id ?? 0)])
        @include('app.partials.form.field.lei-id', ['value' => old('lei_id', $decreto->lei_id ?? $lei_id)])
        @include('app.partials.form.field.nr', ['value' => old('nr', $decreto->nr ?? '')])
        @include('app.partials.form.field.data', ['value' => old('data', $decreto->data ?? '')])
        @include('app.partials.form.field.valor', ['id' => 'vl_credito', 'label' => 'Créditos (R$)', 'value' => old('vl_credito', $decreto->vl_credito ?? 0)])
        @include('app.partials.form.field.valor', ['id' => 'vl_reducao', 'label' => 'Reduções (R$)', 'value' => old('vl_reducao', $decreto->vl_reducao ?? 0)])
        @include('app.partials.form.field.valor', ['id' => 'vl_superavit', 'label' => 'Superávit (R$)', 'value' => old('vl_superavit', $decreto->vl_superavit ?? 0)])
        @include('app.partials.form.field.valor', ['id' => 'vl_excesso', 'label' => 'Excesso (R$)', 'value' => old('vl_excesso', $decreto->vl_excesso ?? 0)])
        @include('app.partials.form.field.valor', ['id' => 'vl_reaberto', 'label' => 'Reaberto (R$)', 'value' => old('vl_reaberto', $decreto->vl_reaberto ?? 0)])
    </div>

    <div class="ui buttons">
        <button type="submit" class="ui positive button enter-as-tab">
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
