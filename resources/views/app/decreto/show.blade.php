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
        <a class="section" href="{{ route('decreto.show', ['id' => $decreto->id]) }}">Decreto nº
            {{ \App\Support\Helpers\Fmt::docnumber($decreto->nr) }}</a>
        <div class="divider"> / </div>
        <div class="active section">Detalhes</div>
    </div>
@endsection

@section('content')

    @include('app.partials.header', [
        'title' => 'Decreto nº ' . \App\Support\Helpers\Fmt::docnumber($decreto->nr),
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
                                    <div @class(["ui label", 'red' => $decreto->vl_credito - $decreto->creditos->sum('valor') != 0])>
                                        diferença:
                                        <div class="detail">
                                            {{ \App\Support\Helpers\Fmt::money($decreto->vl_credito - $decreto->creditos->sum('valor')) }}
                                        </div>
                                    </div>
                                </span>
                            </div>
                            @if (!$decreto->fechado)
                                <a href="{{ route('decreto.creditos', ['decreto_id' => $decreto->id]) }}" class="ui button">
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


        <div class="ui segment">
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
                            <div @class(["ui label", 'red' => $decreto->vl_reducao - $decreto->reducoes->sum('valor') != 0])>
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
                            <div @class(["ui label", 'red' => $decreto->vl_excesso - $decreto->excessos->sum('valor') != 0])>
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
                            <div @class(["ui label", 'red' => $decreto->vl_superavit - $decreto->superavits->sum('valor') != 0])>
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


        <div class="ui menu">
            <div class="item">
                <div class="ui buttons">
                    @if (!$decreto->fechado)
                        <a class="ui labeled icon green button"
                            href="{{ route('decreto.creditos', ['decreto_id' => $decreto->id]) }}">
                            <i class="linkify icon"></i>
                            Vincular
                        </a>
                        <a class="ui labeled icon primary button" href="{{ route('decreto.verify', ['id' => $decreto->id]) }}">
                            <i class="check circle outline icon"></i>
                            Verificar
                        </a>
                    @else
                        <a class="ui labeled icon green button" href="{{ route('decreto.docx', ['id' => $decreto->id]) }}">
                            <i class="file word outline icon"></i>
                            Gerar MS Word
                        </a>
                        <a class="ui labeled icon teal button" href="{{ route('decreto.open', ['id' => $decreto->id]) }}">
                            <i class="door open icon"></i>
                            Reabrir
                        </a>
                    @endif
                </div>
            </div>

            @if (!$decreto->fechado)
                <div class="right item">
                    <div class="ui buttons">
                        <a class="ui labeled icon primary button"
                            href="{{ route('decreto.edit', ['id' => $decreto->id]) }}">
                            <i class="edit icon"></i>
                            Editar
                        </a>
                        <a class="ui labeled icon negative button"
                            href="{{ route('decreto.delete', ['id' => $decreto->id]) }}">
                            <i class="trash icon"></i>
                            Excluir
                        </a>
                    </div>
                </div>
            @endif
        </div>

    @endsection
