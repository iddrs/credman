@extends('app.partials.base')

@section('title', 'Excluir Decreto')

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
        <div class="active section">Excluir</div>
    </div>
@endsection

@section('content')

@include('app.partials.delete', ['title' => "Excluir Decreto nº ".\App\Support\Helpers\Fmt::docnumber($decreto->nr), 'message' => 'Ao excluir este decreto, todos os registros relacionados a ele também serão excluídos.', 'cancel' => route('decreto.show', ['id' => $decreto->id]), 'confirm' => route('decreto.destroy', ['id' => $decreto->id])])
@endsection
