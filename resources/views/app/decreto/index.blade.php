@php
    if (!isset($exercicio)) {
        $exercicio = date('Y');
    }
@endphp

@extends('app.partials.base')

@section('title', 'Decretos')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <div class="active section">Decretos</div>
    </div>
@endsection

@section('content')

    <div class="ui segment">
        @include('app.partials.header', [
            'title' => 'Decretos',
            'subtitle' => $exercicio,
        ])


        @include('app.partials.selector.exercicio', ['route' => $route, 'exercicio' => $exercicio])
    </div>

    <div class="ui segment">

        <table class="ui striped celled table">
            <caption class="ui header">Decretos cadastrados para o exercício de {{ $exercicio }}</caption>
            <thead>
                <tr>
                    {{-- <th class="center aligned">#</th> --}}
                    <th class="right aligned">Nº</th>
                    <th class="center aligned">Data</th>
                    <th class="center aligned">Lei nº</th>
                    <th class="center aligned">Status</th>
                    <th class="right aligned">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($decretos as $decreto)
                    <tr>

                        {{-- <td class="center aligned">{{ $decreto->id }}</td> --}}
                        <td class="right aligned">{{ \App\Support\Helpers\Fmt::docnumber($decreto->nr) }}</td>
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
                        {{-- <td colspan="6">Nenhum decreto cadastrado no exercício de {{ $exercicio }}.</td> --}}
                        <td colspan="5">Nenhum decreto cadastrado no exercício de {{ $exercicio }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
