@php
    if (!isset($exercicio)) {
        $exercicio = date('Y');
    }
@endphp

@extends('app.partials.base')

@section('content')
    @include('app.partials.selector.exercicio', ['route' => $route, 'exercicio' => $exercicio])

    <div class="ui segment">
        <div class="ui five column centered grid">
            <div class="three wide column">
                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalCredito) }}</div>
                    <div class="label">Total de Créditos</div>
                </div>
            </div>
            <div class="three wide column">

                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalSuplementar) }}</div>
                    <div class="label">Total de Suplementar</div>
                </div>
            </div>
            <div class="three wide column">

                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalEspecial) }}</div>
                    <div class="label">Total de Especial</div>
                </div>
            </div>
            <div class="three wide column">

                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalExtraordinario) }}</div>
                    <div class="label">Total de Extraordinário</div>
                </div>
            </div>
            <div class="three wide column">

                <div class="ui tiny statistic">
                    <div class="value">{{ number_format($totalLimiteSuplementacao * 100, 2, ',', '.') }}%</div>
                    <div class="label">Limite de suplementação</div>
                </div>
            </div>
        </div>
    </div>


    <div class="ui segment">
        <div class="ui two column centered grid">
            <div class="column">
                <table class="ui striped celled table">
                    <caption class="ui header">Últimas leis cadastradas</caption>
                    <thead>
                        <tr>
                            <th class="right aligned">Nº</th>
                            <th class="center aligned">Data</th>
                            <th class="center aligned">Exercício</th>
                            <th class="center aligned">Tipo</th>
                            <th class="center aligned">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ultimasLeis as $lei)
                            <tr>
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
                                <td colspan="5">Nenhuma lei cadastrada.</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td class="right aligned" colspan="5">
                                <a class="ui primary basic button" href="{{ route('leis') }}" accesskey="ctrl+L">Ver todas</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="column">
                <table class="ui striped celled table">
                    <caption class="ui header">Últimos decretos cadastrados</caption>
                    <thead>
                        <tr>
                            <th class="right aligned">Nº</th>
                            <th class="center aligned">Data</th>
                            <th class="center aligned">Lei nº</th>
                            <th class="center aligned">Status</th>
                            <th class="right aligned">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ultimosDecretos as $decreto)
                            <tr>

                                <td class="right aligned">
                                    <a href="{{ route('decreto.show', ['id' => $decreto->id]) }}">
                                        {{ \App\Support\Helpers\Fmt::docnumber($decreto->nr) }}
                                    </a>
                                </td>
                                <td class="center aligned">{{ \App\Support\Helpers\Fmt::date($decreto->data) }}</td>
                                <td class="center aligned">{{ \App\Support\Helpers\Fmt::docnumber($decreto->lei) }}</td>
                                <td class="center aligned">
                                    @if ($decreto->fechado)
                                        @include('app.partials.closed')
                                    @else
                                        @include('app.partials.opened')
                                    @endif
                                </td>
                                <td class="center aligned">
                                    <div class="ui buttons">
                                        <a class="ui icon primary button"
                                            href="{{ route('decreto.show', ['id' => $decreto->id]) }}">
                                            <i class="eye icon"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Nenhum decreto cadastrado no exercício de {{ $exercicio }}.</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td class="right aligned" colspan="5">
                                <a class="ui primary basic button" href="{{ route('decretos') }}" accesskey="ctrl+D">Ver todos</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="ui segment">
        <div class="ui four column centered grid">
            <div class="three wide column">
                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalReducao) }}</div>
                    <div class="label">Total por Redução</div>
                </div>
            </div>
            <div class="three wide column">

                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalSuperavit) }}</div>
                    <div class="label">Total por Superávit</div>
                </div>
            </div>
            <div class="three wide column">

                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalExcesso) }}</div>
                    <div class="label">Total por Excesso</div>
                </div>
            </div>
            <div class="three wide column">

                <div class="ui tiny statistic">
                    <div class="value">{{ App\Support\Helpers\Fmt::money($totalReabertura) }}</div>
                    <div class="label">Total por Reabertura</div>
                </div>
            </div>
        </div>
    </div>
@endsection
