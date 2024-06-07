@extends('app.partials.base')

@section('title', 'Verificação do decreto')

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
        <div class="active section">Verificação</div>
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

        <div class="ui middle aligned divided list">
            <div class="item">
                <i class="calendar alternate icon"></i>
                <div class="content">
                    <div class="description">Data:</div>
                    <div class="header">{{ \App\Support\Helpers\Fmt::date($decreto->data) }}</div>
                </div>
            </div>
            <div class="item">
                <i class="calendar icon"></i>
                <div class="content">
                    <div class="description">Status:</div>
                    <div class="header">
                        @if ($decreto->fechado)
                            Fechado
                        @else
                            Aberto
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="ui segment">

        <table class="ui celled compact table">
            <caption class="ui dividing header">Erros detectados</caption>
            <thead>
                <tr>
                    <td colspan="5">
                        <div class="ui message">Erros impedem o fechamento do decreto.</div>
                    </td>
                </tr>
                <tr>
                    <th>Teste</th>
                    <th class="right aligned">Valor 1</th>
                    <th class="right aligned">Valor 2</th>
                    <th class="right aligned">Diferença</th>
                    <th class="center aligned">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($result as $test)
                    <tr @class(['', 'error' => !$test['success']])>
                        <td>{{ $test['name'] }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($test['val1']) }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($test['val2']) }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($test['diff']) }}</td>
                        <td class="center aligned">
                            @if ($test['success'])
                                <i class="check icon"></i>
                            @else
                                <i class="attention icon"></i>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhum erro detectado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="ui segment">

        <table class="ui celled compact table">
            <caption class="ui dividing header">Avisos detectados</caption>
            <thead>
                <tr>
                    <td colspan="5">
                        <div class="ui message">Avisos não impedem o fechamento do decreto. Entretanto, o ideal é não ter avisos.</div>
                    </td>
                </tr>
                <tr>
                    <th>Aviso</th>
                    <th>Justificativa</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warnings as $warn)
                    <tr class="warning">
                        <td>{{ $warn->aviso }}</td>
                        <td>{{ $warn->justificativa }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Nenhum aviso detectado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="ui menu">
        <div class="item">
            <div class="ui buttons">
                @if ($verified)
                    <a class="ui labeled icon green button" href="{{ route('decreto.close', ['id' => $decreto->id]) }}">
                        <i class="door closed icon"></i>
                        Encerrar
                    </a>
                @else
                    <a class="ui grey basic button" href="{{ route('decreto.show', ['id' => $decreto->id]) }}">
                        Voltar
                    </a>
                @endif
            </div>
        </div>
    </div>

@endsection
