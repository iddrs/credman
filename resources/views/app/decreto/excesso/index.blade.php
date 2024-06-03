@extends('app.partials.base')

@section('title', 'Excesso de arrecadação do decreto')

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
        <div class="active section">Excesso de arrecadação</div>
    </div>
@endsection

@section('content')

    @include('app.partials.header', [
        'title' => 'Decreto nº ' . \App\Support\Helpers\Fmt::docnumber($decreto->nr),
    ])

    <div class="ui segment">

        <table class="ui striped celled table">
            <caption class="ui dividing header">Excessos de arrecadação lançados</caption>
            <thead>
                <tr>
                    <th colspan="5">
                        <a href="#receita" class="ui primary button">
                            <i class="plus icon"></i>
                            Novo
                        </a>
                    </th>
                </tr>
                <tr>
                    <th class="center aligned">#</th>
                    <th class="left aligned">Receita</th>
                    <th class="right aligned">Fonte</th>
                    <th class="right aligned">Valor</th>
                    <th class="center aligned">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($decreto->excessos as $excesso)
                    <tr>
                        <td class="center aligned">{{ $excesso->id }}</td>
                        <td class="left aligned">
                            {{ \App\Support\Helpers\Fmt::receita($excesso->receita) }}
                        </td>
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::fonte($excesso->fonte ?? null) }}
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::money($excesso->valor) }}</td>
                        </td>
                        <td class="center aligned">
                            <a href="{{ route('decreto.excesso.delete', ['id' => $excesso->id, 'decreto_id' => $decreto->id]) }}"
                                class="ui red icon button">
                                <i class="trash icon"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhum excesso de arrecadação lançado.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="right aligned">Total lançado</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->excessos->sum('valor'))}}</th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="3" class="right aligned">Total do decreto</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_excesso)}}</th>
                    <th></th>
                </tr>
                <tr class="ui header">
                    <th colspan="3" class="right aligned">Total</th>
                    <th class="right aligned">{{ \App\Support\Helpers\Fmt::money($decreto->vl_excesso - $decreto->excessos->sum('valor'))}}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="ui segment">
        @include('app.partials.form.excesso', [
            'action' => route('decreto.excesso.store', ['decreto_id' => $decreto->id]),
            'title' => 'Novo excesso',
            'excesso' => null,
            'decreto_id' => $decreto->id,
        ])
    </div>

    @if (session('from') == 'decreto.excesso.store')
        <script type="module">
            document.getElementById('receita').focus();
        </script>
    @endif

@endsection
