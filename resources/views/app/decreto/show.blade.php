@extends('app.partials.base')

@section('title', 'Detalhes do decreto')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <a class="section" href="{{ route('leis') }}">Leis</a>
        <div class="divider"> / </div>
        <a class="section" href="{{ route('lei.show', ['id' => $decreto->lei->id]) }}">Lei nº
            {{ \App\Support\Helpers\Fmt::docnumber($decreto->lei->nr) }}</a>
        <div class="divider"> / </div>
        <a class="section" href="{{ route('decreto.show', ['id' => $decreto->id]) }}">{{ \App\Support\Enums\TiposDecreto::getLabel($decreto->tipo_decreto) }} nº
            {{ \App\Support\Helpers\Fmt::docnumber($decreto->nr) }}</a>
        <div class="divider"> / </div>
        <div class="active section">Detalhes</div>
    </div>
@endsection

@section('content')

    @php
        switch ($decreto->tipo_decreto) {
            case 'D':
                $tipo_decreto = 'Decreto';
                break;
            case 'M':
                $tipo_decreto = 'Resolução de Mesa';
                break;

            default:
                $tipo_decreto = 'Indefinido';
                break;
        }
    @endphp

    @include('app.partials.header', [
        'title' => $tipo_decreto . ' nº ' . \App\Support\Helpers\Fmt::docnumber($decreto->nr),
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

    <div class="ui menu">
        @if (!$decreto->fechado)
            <div class="item">
                <div class="ui wrapping spaced buttons">
                    <a class="ui green button"
                        href="{{ route('decreto.creditos', ['decreto_id' => $decreto->id]) }}">
                        Vincular
                    </a>
                    <a class="ui primary button" href="{{ route('decreto.verify', ['id' => $decreto->id]) }}">
                        Verificar
                    </a>
                    <a href="{{ route('decreto.rubricas.update', ['id' => $decreto->id]) }}"
                        class="ui grey basic button">
                        Atualizar rubricas
                    </a>
                @else
                    <div class="right item">
                        <div class="ui wrapping spaced buttons">
                            <a class="ui blue button"
                                href="{{ route('decreto.docx', ['id' => $decreto->id]) }}">
                                Gerar MS Word
                            </a>
                            <a class="ui green button"
                                href="{{ route('decreto.open', ['id' => $decreto->id]) }}">
                                Reabrir
                            </a>
        @endif
    </div>
    </div>

    @if (!$decreto->fechado)
        <div class="right item">
            <div class="ui wrapping spaced buttons">
                <a class="ui blue basic button" href="{{ route('decreto.edit', ['id' => $decreto->id]) }}">
                    Editar
                </a>
                <a class="ui negative basic button" href="{{ route('decreto.delete', ['id' => $decreto->id]) }}">
                    Excluir
                </a>
            </div>
        </div>
    @endif
    </div>

    <div class="ui blue segment">

        <div class="ui grid">
            <div class="stretched row">
                <div class="eight wide column">
                    <div class="ui cards">
                        <div class="ui fluid card">
                            <div class="content">
                                <div class="header">Créditos</div>
                                <div class="description">
                                    <div class="ui statistic">
                                        <div class="value">
                                            {{ \App\Support\Helpers\Fmt::money($decreto->vl_credito) }}
                                        </div>
                                        <div class="label">Total do decreto</div>
                                    </div>
                                </div>

                            </div>
                            <div class="extra content">
                                <span class="left floated">
                                    <div class="ui label">
                                        lançado:
                                        <div class="detail">
                                            {{ \App\Support\Helpers\Fmt::money($decreto->creditos->sum('valor')) }}
                                        </div>
                                    </div>
                                </span>
                                <span class="right floated">
                                    <div @class([
                                        'ui label',
                                        'red' =>
                                            round($decreto->vl_credito - $decreto->creditos->sum('valor'), 2) !=
                                            0.0,
                                    ])>
                                        diferença:
                                        <div class="detail">
                                            {{ \App\Support\Helpers\Fmt::money($decreto->vl_credito - $decreto->creditos->sum('valor')) }}
                                        </div>
                                    </div>
                                </span>
                            </div>
                            @if (!$decreto->fechado)
                                <a href="{{ route('decreto.creditos', ['decreto_id' => $decreto->id]) }}"
                                    class="ui button">
                                    <i class="edit icon"></i> Gerenciar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="eight wide column">
                    <div class="ui fluid card">
                        <div class="content">
                            <div class="ui three statistics">
                                <div class="ui small statistic">
                                    <div class="label">Suplementar</div>
                                    <div class="value">
                                        {{ \App\Support\Helpers\Fmt::money($decreto->creditos->where('tipo', 1)->sum('valor')) }}
                                    </div>
                                </div>
                                <div class="ui small statistic">
                                    <div class="label">Especial</div>
                                    <div class="value">
                                        {{ \App\Support\Helpers\Fmt::money($decreto->creditos->where('tipo', 2)->sum('valor')) }}
                                    </div>
                                </div>
                                <div class="ui small statistic">
                                    <div class="label">Extraordinário</div>
                                    <div class="value">
                                        {{ \App\Support\Helpers\Fmt::money($decreto->creditos->where('tipo', 3)->sum('valor')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="ui violet segment">
        <h2 class="ui dividing header">Origem dos recursos</h2>

        <div class="ui cards">

            <div class="ui horizontal card">
                <div class="content">
                    <div class="header">Redução</div>
                    <div class="description">
                        <div class="ui statistic">
                            <div class="value">
                                {{ \App\Support\Helpers\Fmt::money($decreto->vl_reducao) }}
                            </div>
                            <div class="label">Total do decreto</div>
                        </div>
                    </div>

                </div>
                <div class="extra content">
                    <span class="left floated">
                        <div class="ui label">
                            lançado:
                            <div class="detail">
                                {{ \App\Support\Helpers\Fmt::money($decreto->reducoes->sum('valor')) }}
                            </div>
                        </div>
                    </span>
                    <span class="right floated">
                        <div @class([
                            'ui label',
                            'red' =>
                                round($decreto->vl_reducao - $decreto->reducoes->sum('valor'), 2) !=
                                0.0,
                        ])>
                            diferença:
                            <div class="detail">
                                {{ \App\Support\Helpers\Fmt::money($decreto->vl_reducao - $decreto->reducoes->sum('valor')) }}
                            </div>
                        </div>
                    </span>
                </div>
                @if (!$decreto->fechado)
                    <a href="{{ route('decreto.reducoes', ['decreto_id' => $decreto->id]) }}" class="ui button">
                        <i class="edit icon"></i> Gerenciar
                    </a>
                @endif
            </div>

            <div class="ui horizontal card">
                <div class="content">
                    <div class="header">Excesso de arrecadação</div>
                    <div class="description">
                        <div class="ui statistic">
                            <div class="value">
                                {{ \App\Support\Helpers\Fmt::money($decreto->vl_excesso) }}
                            </div>
                            <div class="label">Total do decreto</div>
                        </div>
                    </div>

                </div>
                <div class="extra content">
                    <span class="left floated">
                        <div class="ui label">
                            lançado:
                            <div class="detail">
                                {{ \App\Support\Helpers\Fmt::money($decreto->excessos->sum('valor')) }}
                            </div>
                        </div>
                    </span>
                    <span class="right floated">
                        <div @class([
                            'ui label',
                            'red' =>
                                round($decreto->vl_excesso - $decreto->excessos->sum('valor'), 2) !=
                                0.0,
                        ])>
                            diferença:
                            <div class="detail">
                                {{ \App\Support\Helpers\Fmt::money($decreto->vl_excesso - $decreto->excessos->sum('valor')) }}
                            </div>
                        </div>
                    </span>
                </div>
                @if (!$decreto->fechado)
                    <a href="{{ route('decreto.excessos', ['decreto_id' => $decreto->id]) }}" class="ui button">
                        <i class="edit icon"></i> Gerenciar
                    </a>
                @endif
            </div>

        </div>


        <div class="ui cards">

            <div class="ui horizontal card">
                <div class="content">
                    <div class="header">Superávit financeiro</div>
                    <div class="description">
                        <div class="ui statistic">
                            <div class="value">
                                {{ \App\Support\Helpers\Fmt::money($decreto->vl_superavit) }}
                            </div>
                            <div class="label">Total do decreto</div>
                        </div>
                    </div>

                </div>
                <div class="extra content">
                    <span class="left floated">
                        <div class="ui label">
                            lançado:
                            <div class="detail">
                                {{ \App\Support\Helpers\Fmt::money($decreto->superavits->sum('valor')) }}
                            </div>
                        </div>
                    </span>
                    <span class="right floated">
                        <div @class([
                            'ui label',
                            'red' =>
                                round($decreto->vl_superavit - $decreto->superavits->sum('valor'), 2) !=
                                0.0,
                        ])>
                            diferença:
                            <div class="detail">
                                {{ \App\Support\Helpers\Fmt::money($decreto->vl_superavit - $decreto->superavits->sum('valor')) }}
                            </div>
                        </div>
                    </span>
                </div>
                @if (!$decreto->fechado)
                    <a href="{{ route('decreto.superavits', ['decreto_id' => $decreto->id]) }}" class="ui button">
                        <i class="edit icon"></i> Gerenciar
                    </a>
                @endif
            </div>

            <div class="ui horizontal card">
                <div class="content">
                    <div class="header">Reabertura</div>
                    <div class="description">
                        <div class="ui statistic">
                            <div class="value">
                                {{ \App\Support\Helpers\Fmt::money($decreto->vl_reaberto) }}
                            </div>
                            <div class="label">Total do decreto</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>




@endsection
