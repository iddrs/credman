<div class="ui buttons">
    <a class="ui labeled icon basic button" href="{{ route($route, ['exercicio' => $exercicio - 1]) }}">
        <i class="left chevron icon"></i>
        {{ $exercicio - 1 }}
    </a>
    <button class="ui button">
        {{ $exercicio }}
    </button>
    <a class="ui right labeled icon basic button" href="{{ route($route, ['exercicio' => $exercicio + 1]) }}">
        {{ $exercicio + 1 }}
        <i class="right chevron icon"></i>
    </a>
</div>
