@extends('app.partials.base')

@section('title', 'Superávits financeiros do decreto')

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
        <div class="active section">Superávits financeiros</div>
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
            <caption class="ui left aligned header">Superávits financeiros lançados</caption>
            <thead>
                <tr>
                    {{-- <th colspan="4"> --}}
                    <th colspan="3">
                        <a href="#valor" class="ui primary button" accesskey="ctrl+a">
                            Novo
                        </a>
                    </th>
                </tr>
                <tr>
                    {{-- <th class="center aligned">#</th> --}}
                    <th class="right aligned">Fonte</th>
                    <th class="right aligned">Valor</th>
                    <th class="center aligned">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($decreto->superavits as $superavit)
                    <tr>
                        {{-- <td class="center aligned">{{ $superavit->id }}</td> --}}
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::fonte($superavit->fonte) }}
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($superavit->valor) }}</td>
                        </td>
                        <td class="center aligned">
                            <a href="{{ route('decreto.superavit.delete', ['id' => $superavit->id, 'decreto_id' => $decreto->id]) }}"
                                class="ui red icon basic button">
                                <i class="trash icon"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- <td colspan="5">Nenhum superávit financeiro lançado.</td> --}}
                        <td colspan="4">Nenhum superávit financeiro lançado.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    {{-- <th colspan="2" class="right aligned">Total lançado</th> --}}
                    <th class="right aligned">Total lançado</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->superavits->sum('valor'))}}</th>
                    <th></th>
                </tr>
                <tr>
                    {{-- <th colspan="2" class="right aligned">Total do decreto</th> --}}
                    <th class="right aligned">Total do decreto</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_superavit)}}</th>
                    <th></th>
                </tr>
                <tr class="ui header">
                    {{-- <th colspan="2" class="right aligned">Total a lançar</th> --}}
                    <th class="right aligned">Total a lançar</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_superavit - $decreto->superavits->sum('valor'))}}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="ui segment">
        @include('app.partials.form.superavit', [
            'action' => route('decreto.superavit.store', ['decreto_id' => $decreto->id]),
            'title' => 'Novo superavit',
            'superavit' => null,
            'decreto_id' => $decreto->id,
        ])
    </div>

    @if (session('from') == 'decreto.superavit.store')
        <script type="module">
            document.getElementById('valor').focus();
        </script>
    @endif

@endsection
