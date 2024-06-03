@extends('app.partials.base')

@section('title', 'Vincular superávits do decreto')

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
        <a class="section" href="{{ route('decreto.creditos', ['decreto_id' => $decreto->id]) }}">Vincular</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route('decreto.superavits', ['decreto_id' => $decreto->id]) }}">Superávits</a>
        <div class="divider"> / </div>
        <div class="active section">Acesso nº {{ $credito->acesso }}</div>
    </div>
@endsection

@section('content')

    @include('app.partials.header', [
        'title' => 'Acesso nº ' . $credito->acesso,
        'subtitle' => 'Decreto nº ' . \App\Support\Helpers\Fmt::docnumber($decreto->nr),
    ])


    <div class="ui segment">
        @include('app.partials.header', [
            'title' => 'Dados do acesso',
            'subtitle' => $credito->acesso,
            'level' => 2,
        ])

        <table class="ui striped compact table">
            <tbody>
                <tr>
                    <td>Valor do crédito</td>
                    <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($credito->valor) }}</td>
                </tr>
                <tr>
                    <td>Valor vinculado</td>
                    <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($credito->vinculos->sum('valor')) }}</td>
                </tr>
                <tr @class([
                    'red',
                    'green' =>
                        round($credito->valor == $credito->vinculos->sum('valor'), 2) !== 0.0,
                ])>
                    <td>Saldo para vincular</td>
                    <td class="right aligned">
                        {{ \App\Support\Helpers\Fmt::money($credito->valor - $credito->vinculos->sum('valor')) }}
                    </td>
                </tr>

        </table>

    </div>


    <div class="ui segment">

        <table class="ui striped celled table">
            <caption class="ui dividing header">Superávits na mesma fonte a vincular</caption>
            <thead>
                <tr>
                    <th class="center aligned">#</th>
                    <th class="right aligned">Fonte</th>
                    <th class="right aligned">Valor disponível</th>
                    <th class="center aligned">Vincular</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($superavits_fonte as $item)
                    @if ($item->valor === $item->utilizado)
                        @continue
                    @endif
                    <tr>
                        <td class="center aligned">{{ $item->id }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::fonte($item->fonte ?? null) }}
                        </td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($item->valor - $item->utilizado) }}
                        </td>
                        <td class="right aligned">
                            @if ($item->valor != $item->utilizado)
                                @php
                                    $saldo = $item->valor - $item->utilizado;
                                    $maximo = $credito->valor - $credito->vinculos->sum('valor');
                                    $valor = $saldo;
                                    if ($saldo > $maximo) {
                                        $valor = $maximo;
                                    }
                                @endphp
                                <form
                                    action="{{ route('decreto.credito.vincular.superavit.confirm', ['superavit_id' => $item->id, 'decreto_id' => $decreto->id, 'credito_id' => $credito->id]) }}"
                                    method="POST" class="ui action input">
                                    @csrf
                                    <input type="number" name="valor" step="0.01" min="0.01" required
                                        id="valor" value="{{ $valor }}" max="{{ $maximo }}">

                                    <button type="submit" class="ui icon primary button enter-as-tab">
                                        <i class="linkify icon"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhum superávit na mesma fonte com saldo para vincular.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <div class="ui segment">

        <table class="ui striped celled table">
            <caption class="ui dividing header">Superávits vinculados</caption>
            <thead>
                <tr>
                    <th class="center aligned">#</th>
                    <th class="right aligned">Fonte</th>
                    <th class="right aligned">Valor</th>
                    <th class="center aligned">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($credito->vinculos->whereNotNull('superavit_id') as $item)
                    <tr @class([
                        '',
                        'red' => $item->superavit->fonte != $credito->rubrica->fonte,
                    ])>
                        <td class="center aligned">{{ $item->id }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::fonte($item->superavit->fonte ?? null) }}
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($item->valor) }}</td>
                        </td>
                        <td class="right aligned">
                            <a href="{{ route('decreto.credito.vinculo.delete', ['id' => $item->id, 'decreto_id' => $decreto->id, 'credito_id' => $credito->id]) }}"
                                class="ui red icon button">
                                <i class="trash icon"></i>
                            </a>
                        </td>
                    </tr>
                    @if (!is_null($item->justificativa))
                        <tr class="red">
                            <td colspan="4">
                                <div class="ui label">
                                    <i class="info circle icon"></i>
                                    Justificativa:
                                </div>
                                {{ $item->justificativa }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5">Nenhum superávit vinculado.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="ui header">
                    <th class="right aligned" colspan="2">Total</th>
                    <th class="right aligned">
                        {{ \App\Support\Helpers\Fmt::money($decreto->vinculos->whereNotNull('superavit_id')->where('credito_id', $credito->id)->sum('valor')) }}
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="ui segment">

        <table class="ui striped celled table">
            <caption class="ui dividing header">Superávits em outras fontes a vincular</caption>
            <thead>
                <tr>
                    <th class="center aligned">#</th>
                    <th class="right aligned">Fonte</th>
                    <th class="right aligned">Valor disponível</th>
                    <th class="center aligned">Vincular</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($superavits_outros as $item)
                    <tr>
                        <td class="center aligned">{{ $item->id }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::fonte($item->fonte ?? null) }}
                        </td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($item->valor - $item->utilizado) }}
                        </td>
                        <td class="right aligned">
                            @if ($item->valor != $item->utilizado)
                                @php
                                    $saldo = $item->valor - $item->utilizado;
                                    $maximo = $credito->valor - $credito->vinculos->sum('valor');
                                    $valor = $saldo;
                                    if ($saldo > $maximo) {
                                        $valor = $maximo;
                                    }
                                @endphp
                                <form
                                    action="{{ route('decreto.credito.vincular.superavit.confirm', ['superavit_id' => $item->id, 'decreto_id' => $decreto->id, 'credito_id' => $credito->id]) }}"
                                    method="POST" class="ui action input">
                                    @csrf
                                    <input type="number" name="valor" step="0.01" min="0.01" required
                                        id="valor" value="{{ $valor }}" max="{{ $maximo }}">

                                    <button type="submit" class="ui icon red button enter-as-tab">
                                        <i class="linkify icon"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhum superávit em outras fontes com saldo para vincular.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


@endsection
