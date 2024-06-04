@extends('app.partials.base')

@section('title', 'Leis')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <div class="active section">Leis</div>
    </div>
@endsection

@section('content')

<div class="ui segment">
    @include('app.partials.header', [
        'title' => 'Leis'
        ])
</div>

<div class="ui segment">

    <table class="ui striped celled table">
        <caption class="ui header">Leis cadastradas</caption>
        <thead>
            <tr>
                {{-- <th colspan="6"> --}}
                <th colspan="5">
                    <a href="#nr" class="ui primary button">
                        <i class="plus icon"></i>
                        Novo
                    </a>
                </th>
            </tr>
            <tr>
                {{-- <th class="center aligned">#</th> --}}
                <th class="right aligned">Nº</th>
                <th class="center aligned">Data</th>
                <th class="center aligned">Exercício</th>
                <th class="center aligned">Tipo</th>
                <th class="center aligned">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leis as $lei)
                <tr>
                    {{-- <td class="center aligned">{{ $lei->id }}</td> --}}
                    <td class="right aligned">
                        <a href="{{ route('lei.show', ['id' => $lei->id]) }}">
                            {{ \App\Support\Helpers\Fmt::docnumber($lei->nr) }}
                        </a>
                    </td>
                    <td class="center aligned">{{ \App\Support\Helpers\Fmt::date($lei->data) }}</td>
                    <td class="center aligned">{{ $lei->exercicio }}</td>
                    <td class="center aligned">{{ $lei->tipo }}</td>
                    <td class="center aligned">
                        <div class="ui buttons">
                            <a href="{{ route('lei.show', ['id' => $lei->id]) }}" class="ui primary icon button">
                                <i class="eye icon"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    {{-- <td colspan="6">Nenhuma lei cadastrada.</td> --}}
                    <td colspan="5">Nenhuma lei cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<div class="ui segment">
    @include('app.partials.form.lei', ['action' => route('lei.store'), 'title' => 'Nova lei', 'lei' => null])
</div>

@if (session('from') == 'lei.store')
    <script type="module">
        document.getElementById('nr').focus();
    </script>
@endif

@endsection
