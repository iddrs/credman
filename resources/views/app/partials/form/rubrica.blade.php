<form action="{{ $action }}" method="post" class="ui form">

    @include('app.partials.header', ['title' => $title])

    @csrf

    <div class="fields">
        @include('app.partials.form.field.id', ['value' => old('id', $rubrica->id ?? 0)])
        @include('app.partials.form.field.acesso', ['value' => old('acesso', $rubrica->acesso ?? '')])
        @include('app.partials.form.field.projativ', ['value' => old('projativ', $rubrica->projativ ?? '')])
        @include('app.partials.form.field.despesa', ['value' => old('despesa', $rubrica->despesa ?? '')])
        @include('app.partials.form.field.uniorcam', ['value' => old('uniorcam', $rubrica->uniorcam ?? '')])
        @include('app.partials.form.field.fonte', ['value' => old('fonte', $rubrica->fonte ?? '')])
        @include('app.partials.form.field.complemento', ['value' => old('complemento', $rubrica->complemento ?? '')])
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
