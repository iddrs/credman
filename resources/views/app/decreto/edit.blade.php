@extends('app.partials.base')

@section('title', 'Edição do decreto')

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
        <div class="active section">Editar</div>
    </div>
@endsection

@section('content')

    <div class="ui segment">
        @include('app.partials.header', [
            'title' => 'Decreto nº ' . \App\Support\Helpers\Fmt::docnumber($decreto->nr),
            'subtitle' => 'Editar',
        ])


        @include('app.partials.form.decreto', [
            'action' => route('decreto.update'),
            'title' => 'Edição',
            'cancel' => route('decreto.show', ['id' => $decreto->id]),
        ])

    </div>

@endsection
