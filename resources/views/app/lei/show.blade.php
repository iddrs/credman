@extends('app.partials.base')

@section('title', 'Detalhes da lei')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <a class="section" href="{{ route('leis') }}">Leis</a>
        <div class="divider"> / </div>
        <div class="active section">Lei nº {{ \App\Support\Helpers\Fmt::docnumber($lei->nr) }}</div>
    </div>
@endsection

@section('content')
    @include('app.partials.header', [
        'title' => 'Lei nº ' . \App\Support\Helpers\Fmt::docnumber($lei->nr),
        'subtitle' => 'Detalhes',
    ])

    <div class="ui segment">


        <div class="ui middle aligned divided list">
            <div class="item">
                <i class="calendar alternate icon"></i>
                <div class="content">
                    <div class="description">Data:</div>
                    <div class="header">{{ \App\Support\Helpers\Fmt::date($lei->data) }}</div>
                </div>
            </div>
            <div class="item">
                <i class="calendar icon"></i>
                <div class="content">
                    <div class="description">Exercício:</div>
                    <div class="header">{{ $lei->exercicio }}</div>
                </div>
            </div>
            <div class="item">
                <i class="tag icon"></i>
                <div class="content">
                    <div class="description">Tipo:</div>
                    <div class="header">{{ $lei->tipo }}</div>
                </div>
            </div>
            <div class="item">
                <i class="tag icon"></i>
                <div class="content">
                    <div class="description">BC Limite:</div>
                    <div class="header">{{ \App\Support\Helpers\Fmt::money($lei->bc_limite) }}</div>
                </div>
            </div>
        </div>

        <div class="ui buttons">
            <a class="ui labeled icon primary button" href="{{ route('lei.edit', ['id' => $lei->id]) }}">
                <i class="edit icon"></i>
                Editar
            </a>
            <a class="ui labeled icon negative button" href="{{ route('lei.delete', ['id' => $lei->id]) }}">
                <i class="trash icon"></i>
                Excluir
            </a>
        </div>

    </div>

    <div class="ui segment">

        <table class="ui striped celled table">

            <caption class="ui dividing header">Decretos vinculados a esta lei</caption>

            <thead>
                <tr>
                    <th colspan="5">
                        <a class="ui labeled icon primary button" href="#nr">
                            <i class="plus icon"></i>
                            Novo decreto
                        </a>
                    </th>
                </tr>
                <tr>
                    <th class="center aligned one wide">#</th>
                    <th class="right aligned three wide">Nº</th>
                    <th class="right aligned three wide">Data</th>
                    <th class="right aligned two wide">Status</th>
                    <th class="center aligned three wide">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($lei->decretos as $decreto)
                    <tr>
                        <td class="center aligned">{{ $decreto->id }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::docnumber($decreto->nr) }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::date($decreto->data) }}</td>
                        <td class="right aligned">
                            @if ($decreto->fechado)
                                @include('app.partials.closed')
                            @else
                            @include('app.partials.opened')
                            @endif
                        </td>
                        <td class="center aligned">
                            <div class="ui buttons">
                                <a class="ui icon primary button" href="{{ route('decreto.show', ['id' => $decreto->id]) }}">
                                    <i class="eye icon"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhum decreto vinculado.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div class="ui segment">
        @include('app.partials.form.decreto', [
            'action' => route('decreto.store'),
            'title' => 'Novo decreto',
            'lei_id' => $lei->id,
        ])
    </div>

    @if (session('from') == 'decreto.store')
        <script type="module">
            document.getElementById('nr').focus();
        </script>
    @endif

@endsection
