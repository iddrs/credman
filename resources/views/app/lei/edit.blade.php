@extends('app.partials.base')

@section('title', 'Edição da lei')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <a class="section" href="{{ route('leis') }}">Leis</a>
        <div class="divider"> / </div>
        <a class="section" href="{{ route('lei.show', ['id' => $lei->id]) }}">Lei nº
            {{ \App\Support\Helpers\Fmt::docnumber($lei->nr) }}</a>
        <div class="divider"> / </div>
        <div class="active section">Editar</div>
    </div>
@endsection

@section('content')

    <div class="ui segment">
        @include('app.partials.header', [
            'title' => 'Lei nº ' . \App\Support\Helpers\Fmt::docnumber($lei->nr),
            'subtitle' => 'Editar',
        ])


        @include('app.partials.form.lei', [
            'action' => route('lei.update'),
            'title' => 'Edição',
            'cancel' => route('lei.show', ['id' => $lei->id]),
        ])

    </div>

@endsection
