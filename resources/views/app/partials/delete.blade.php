<div class="ui segment">
    @include('app.partials.header', [
        'title' => $title,
        'subtitle' => 'Confirmar exclusão'
        ])

        <div class="ui warnig message">
            <div class="header">Tem certeza que deseja excluir?</div>
            <p>{{ $message }}</p>
        </div>

    <div class="ui buttons">
        <a href="{{ $confirm }}" class="ui red button">Sim</a>
        <a href="{{ $cancel }}" class="ui grey basic button">Cancelar</a>
    </div>
</div>
