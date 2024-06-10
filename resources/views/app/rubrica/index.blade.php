@php
    if(!isset($exercicio)) $exercicio = date('Y');
@endphp

@extends('app.partials.base')

@section('title', 'Rubricas')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <div class="active section">Rubricas</div>
    </div>
@endsection

@section('content')

<div class="ui segment">
    @include('app.partials.header', [
        'title' => 'Rubricas',
        'subtitle' => $exercicio
        ])


    @include('app.partials.selector.exercicio', ['route' => $route, 'exercicio' => $exercicio])
</div>

<div class="ui segment">

    <table class="ui striped celled table">
        <caption class="ui left aligned header">Rubricas cadastradas em {{ $exercicio }}</caption>
        <thead>
            <tr>
                {{-- <th colspan="8"> --}}
                <th colspan="7">
                    <a href="#acesso" class="ui primary button" accesskey="ctrl+a">
                        {{-- <i class="plus icon"></i> --}}
                        Novo
                    </a>
                </th>
            </tr>
            <tr>
                {{-- <th class="center aligned">#</th> --}}
                <th class="right aligned">Acesso</th>
                <th class="center aligned">Unid. Orç.</th>
                <th class="center aligned">Proj./Ativ.</th>
                <th class="center aligned">Despesa</th>
                <th class="center aligned">Fonte</th>
                <th class="center aligned">Complemento</th>
                <th class="center aligned">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rubricas as $rubrica)
                <tr>
                    {{-- <td class="center aligned">{{ $rubrica->id }}</td> --}}
                    <td class="right aligned">{{ $rubrica->acesso }}</td>
                    <td class="center aligned">{{ \App\Support\Helpers\Fmt::uniorcam($rubrica->uniorcam) }}</td>
                    <td class="center aligned">{{ \App\Support\Helpers\Fmt::projativ($rubrica->projativ) }}</td>
                    <td class="center aligned">{{ \App\Support\Helpers\Fmt::despesa($rubrica->despesa) }}</td>
                    <td class="center aligned">{{ \App\Support\Helpers\Fmt::fonte($rubrica->fonte) }}</td>
                    <td class="center aligned">{{ $rubrica->complemento }}</td>
                    <td class="center aligned">
                        <div class="ui wrapping spaced buttons">
                            <a href="{{ route('rubrica.edit', ['id' => $rubrica->id]) }}" class="ui blue icon basic button">
                                <i class="edit icon"></i>
                            </a>
                            <a href="{{ route('rubrica.delete', ['id' => $rubrica->id]) }}" class="ui negative icon basic button">
                                <i class="trash icon"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    {{-- <td colspan="8">Nenhuma rubrica cadastrada no ano de {{ $exercicio }}.</td> --}}
                    <td colspan="7">Nenhuma rubrica cadastrada no ano de {{ $exercicio }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<div class="ui segment">
    @include('app.partials.form.rubrica', ['action' => route('rubrica.store', ['exercicio' => $exercicio]), 'title' => 'Nova rubrica', 'rubrica' => null])
</div>

@if (session('from') == 'rubrica.store')
    <script type="module">
        document.getElementById('acesso').focus();
    </script>
@endif

@endsection
