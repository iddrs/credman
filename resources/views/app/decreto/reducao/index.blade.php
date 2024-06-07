@extends('app.partials.base')

@section('title', 'Reduções do decreto')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <a class="section" href="{{ route('leis') }}">Leis</a>
        <div class="divider"> / </div>
        <a class="section" href="{{ route('lei.show', ['id' => $decreto->lei->id]) }}">Lei nº
            {{ \App\Support\Helpers\Fmt::docnumber($decreto->lei->nr) }}</a>
        <div class="divider"> / </div>
        <a class="section" href="{{ route('decreto.show', ['id' => $decreto->id]) }}">{{\App\Support\Enums\TiposDecreto::getLabel($decreto->tipo_decreto)}} nº
            {{ \App\Support\Helpers\Fmt::docnumber($decreto->nr) }}</a>
        <div class="divider"> / </div>
        <div class="active section">Reduções</div>
    </div>
@endsection

@section('content')

@php
    $tipo = \App\Support\Enums\TiposDecreto::getLabel($decreto->tipo_decreto);
@endphp
    @include('app.partials.header', [
        'title' => $tipo.' nº ' . \App\Support\Helpers\Fmt::docnumber($decreto->nr),
    ])

    <div class="ui segment">

        <table class="ui striped celled table">
            <caption class="ui dividing header">Reduções lançadas</caption>
            <thead>
                <tr>
                    {{-- <th colspan="11"> --}}
                    <th colspan="10">
                        <a href="#acesso" class="ui primary button" accesskey="ctrl+a">
                            Nova
                        </a>
                        <a href="{{ route('decreto.reducoes.rubricas.update', ['decreto_id' => $decreto->id]) }}" class="ui grey basic button">
                            Atualizar rubricas
                        </a>
                    </th>
                </tr>
                <tr>
                    {{-- <th class="center aligned">#</th> --}}
                    <th class="right aligned">Acesso</th>
                    <th class="right aligned">Unid. Orç.</th>
                    <th class="right aligned">Proj./Ativ.</th>
                    <th class="right aligned">Despesa</th>
                    <th class="right aligned">Valor</th>
                    <th class="right aligned">Fonte</th>
                    <th class="right aligned">Complemento</th>
                    <th class="center aligned">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($decreto->reducoes as $reducao)
                    <tr @class(['', 'error' => $reducao->rubrica_id == 0])>
                        {{-- <td class="center aligned">{{ $reducao->id }}</td> --}}
                        <td class="right aligned">{{ $reducao->acesso }}</td>
                        <td class="right aligned">
                            {{ \App\Support\Helpers\Fmt::uniorcam($reducao->rubrica->uniorcam ?? null) }}</td>
                        <td class="right aligned">
                            {{ \App\Support\Helpers\Fmt::projativ($reducao->rubrica->projativ ?? null) }}</td>
                        <td class="right aligned">
                            {{ \App\Support\Helpers\Fmt::despesa($reducao->rubrica->despesa ?? null) }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($reducao->valor) }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::fonte($reducao->rubrica->fonte ?? null) }}
                        </td>
                        <td class="right aligned">{{ $reducao->rubrica->complemento ?? '' }}</td>
                        <td class="center aligned">
                            <a href="{{ route('decreto.reducao.delete', ['id' => $reducao->id, 'decreto_id' => $decreto->id]) }}"
                                class="ui red icon basic button">
                                <i class="trash icon"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- <td colspan="11">Nenhuma redução lançada.</td> --}}
                        <td colspan="10">Nenhuma redução lançada.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    {{-- <th class="right aligned" colspan="5">Total lançado</th> --}}
                    <th class="right aligned" colspan="4">Total lançado</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->reducoes->sum('valor')) }}</th>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    {{-- <th class="right aligned" colspan="5">Total do decreto</th> --}}
                    <th class="right aligned" colspan="4">Total do decreto</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_reducao) }}</th>
                    <th colspan="3"></th>
                </tr>
                <tr class="ui header">
                    {{-- <th class="right aligned" colspan="5">Total a lançar</th> --}}
                    <th class="right aligned" colspan="4">Total a lançar</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_reducao - $decreto->reducoes->sum('valor')) }}</th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="ui segment">
        @include('app.partials.form.reducao', [
            'action' => route('decreto.reducao.store', ['decreto_id' => $decreto->id]),
            'title' => 'Nova redução',
            'reducao' => null,
            'decreto_id' => $decreto->id,
        ])
    </div>

    @if (session('from') == 'decreto.reducao.store')
        <script type="module">
            document.getElementById('acesso').focus();
        </script>
    @endif

@endsection
