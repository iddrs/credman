@extends('app.partials.base')

@section('title', 'Créditos do decreto')

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
        <div class="active section">Créditos</div>
    </div>
@endsection

@section('content')

    @include('app.partials.header', [
        'title' => 'Decreto nº ' . \App\Support\Helpers\Fmt::docnumber($decreto->nr),
    ])

    <div class="ui segment">

        <table class="ui striped celled table">
            <caption class="ui dividing header">Créditos lançados</caption>
            <thead>
                <tr>
                    {{-- <th colspan="11"> --}}
                    <th colspan="10">
                        <a href="#acesso" class="ui primary button" accesskey="F8">
                            <i class="plus icon"></i>
                            Novo
                        </a>
                        <a href="{{ route('decreto.creditos.rubricas.update', ['decreto_id' => $decreto->id]) }}"
                            class="ui button">
                            <i class="search icon"></i>
                            Atualizar rubricas
                        </a>
                    </th>
                </tr>
                <tr>
                    <td colspan="11" class="right aligned">
                        <div class="ui basic label">
                            <i class="ui green thumbs up icon"></i>
                            Saldo totalmente vinculado.
                        </div>
                        <div class="ui basic label">
                            <i class="ui blue linkify icon"></i>
                            Possui saldo a vincular.
                        </div>
                    </td>
                </tr>
                <tr>
                    {{-- <th class="center aligned">#</th> --}}
                    <th class="right aligned">Acesso</th>
                    <th class="right aligned">Unid. Orç.</th>
                    <th class="right aligned">Proj./Ativ.</th>
                    <th class="right aligned">Despesa</th>
                    <th class="right aligned">Valor</th>
                    <th class="left aligned">Tipo</th>
                    <th class="left aligned">Origem</th>
                    <th class="right aligned">Fonte</th>
                    <th class="right aligned">Complemento</th>
                    <th class="right aligned">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($decreto->creditos as $credito)
                    <tr @class(['', 'error' => $credito->rubrica_id == 0])>
                        {{-- <td class="center aligned">{{ $credito->id }}</td> --}}
                        <td class="right aligned">{{ $credito->acesso }}</td>
                        <td class="right aligned">
                            {{ \App\Support\Helpers\Fmt::uniorcam($credito->rubrica->uniorcam ?? null) }}
                        </td>
                        <td class="right aligned">
                            {{ \App\Support\Helpers\Fmt::projativ($credito->rubrica->projativ ?? null) }}
                        </td>
                        <td class="right aligned">
                            {{ \App\Support\Helpers\Fmt::despesa($credito->rubrica->despesa ?? null) }}
                        </td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($credito->valor) }}</td>
                        <td class="left aligned">{{ \App\Support\Enums\TiposCredito::toArray()[$credito->tipo] }}</td>
                        <td class="left aligned">{{ \App\Support\Enums\TiposOrigem::toArray()[$credito->origem] }}</td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::fonte($credito->rubrica->fonte ?? null) }}
                        </td>
                        <td class="right aligned">{{ $credito->rubrica->complemento ?? '' }}</td>
                        <td class="right aligned">
                            <div class="ui wrapping spaced buttons">
                                @switch($credito->origem)
                                    @case(1)
                                        @if ($credito->valor - $credito->vinculos->sum('valor') != 0)
                                            <a @class([
                                                'ui blue icon button',
                                                'disabled' => is_null($credito->rubrica),
                                            ])
                                                href="{{ route('decreto.credito.vincular.reducao', ['credito_id' => $credito->id, 'decreto_id' => $decreto->id]) }}">
                                                <i class="linkify icon"></i>
                                            </a>
                                        @else
                                            <a @class([
                                                'ui green icon button',
                                                'disabled' => is_null($credito->rubrica),
                                            ])
                                                href="{{ route('decreto.credito.vincular.reducao', ['credito_id' => $credito->id, 'decreto_id' => $decreto->id]) }}">
                                                <i class="thumbs up icon"></i>
                                            </a>
                                        @endif
                                    @break

                                    @case(2)
                                        @if ($credito->valor - $credito->vinculos->sum('valor') != 0)
                                            <a @class([
                                                'ui blue icon button',
                                                'disabled' => is_null($credito->rubrica),
                                            ])
                                                href="{{ route('decreto.credito.vincular.superavit', ['credito_id' => $credito->id, 'decreto_id' => $decreto->id]) }}">
                                                <i class="linkify icon"></i>
                                            </a>
                                        @else
                                            <a @class([
                                                'ui green icon button',
                                                'disabled' => is_null($credito->rubrica),
                                            ])
                                                href="{{ route('decreto.credito.vincular.superavit', ['credito_id' => $credito->id, 'decreto_id' => $decreto->id]) }}">
                                                <i class="thumbs up icon"></i>
                                            </a>
                                        @endif
                                    @break

                                    @case(3)
                                        @if ($credito->valor - $credito->vinculos->sum('valor') != 0)
                                            <a @class([
                                                'ui blue icon button',
                                                'disabled' => is_null($credito->rubrica),
                                            ])
                                                href="{{ route('decreto.credito.vincular.excesso', ['credito_id' => $credito->id, 'decreto_id' => $decreto->id]) }}">
                                                <i class="linkify icon"></i>
                                            </a>
                                        @else
                                            <a @class([
                                                'ui green icon button',
                                                'disabled' => is_null($credito->rubrica),
                                            ])
                                                href="{{ route('decreto.credito.vincular.excesso', ['credito_id' => $credito->id, 'decreto_id' => $decreto->id]) }}">
                                                <i class="thumbs up icon"></i>
                                            </a>
                                        @endif
                                    @break

                                    @default
                                        @php
                                            $rota = 'decreto.creditos';
                                        @endphp
                                @endswitch


                                <a href="{{ route('decreto.credito.delete', ['id' => $credito->id, 'decreto_id' => $decreto->id]) }}"
                                    class="ui red icon button">
                                    <i class="trash icon"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            {{-- <td colspan="11">Nenhum crédito lançado.</td> --}}
                            <td colspan="10">Nenhum crédito lançado.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        {{-- <th colspan="5" class="right aligned"> --}}
                        <th colspan="4" class="right aligned">
                            Total lançado
                        </th>
                        <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->creditos->sum('valor')) }}</th>
                        <th colspan="5"></th>
                    </tr>
                    <tr>
                        {{-- <th colspan="5" class="right aligned"> --}}
                        <th colspan="4" class="right aligned">
                            Total do decreto
                        </th>
                        <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_credito) }}</th>
                        <th colspan="5"></th>
                    </tr>
                    <tr class="ui header">
                        {{-- <th colspan="5" class="right aligned"> --}}
                        <th colspan="4" class="right aligned">
                            Saldo a lançar
                        </th>
                        <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_credito - $decreto->creditos->sum('valor')) }}</th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
            </table>
        </div>


        <div class="ui segment">
            @include('app.partials.form.credito', [
                'action' => route('decreto.credito.store', ['decreto_id' => $decreto->id]),
                'title' => 'Novo crédito',
                'credito' => null,
                'decreto_id' => $decreto->id,
            ])
        </div>

        @if (session('from') == 'decreto.credito.store')
            <script type="module">
                document.getElementById('acesso').focus();
            </script>
        @endif

    @endsection
