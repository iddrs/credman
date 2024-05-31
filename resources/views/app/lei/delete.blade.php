@extends('app.partials.base')

@section('title', 'Excluir Lei')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <a class="section" href="{{ route('leis') }}">Leis</a>
        <div class="divider"> / </div>
        <a class="section" href="{{ route('lei.show', ['id' => $lei->id]) }}">Lei nº {{ \App\Support\Helpers\Fmt::docnumber($lei->nr) }}</a>
        <div class="divider"> / </div>
        <div class="active section">Excluir</div>
    </div>
@endsection

@section('content')

@include('app.partials.delete', ['title' => "Excluir Lei nº ".\App\Support\Helpers\Fmt::docnumber($lei->nr), 'message' => 'Ao excluir esta lei, todos os registros relacionados a ela também serão excluídos.', 'cancel' => route('lei.show', ['id' => $lei->id]), 'confirm' => route('lei.destroy', ['id' => $lei->id])])
@endsection
