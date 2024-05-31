@extends('app.partials.base')

@section('title', 'Confirmar vinculação do decreto')

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
        <a class="section" href="{{ route('decreto.creditos', ['decreto_id' => $decreto->id]) }}">Créditos</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Confirmar vinculação</div>
    </div>
@endsection

@section('content')

<div class="ui segment">
    @include('app.partials.header', [
        'title' => 'Potencial problema ao vincular o decreto nº '.\App\Support\Helpers\Fmt::docnumber($decreto->nr),
        ])

        <div class="ui error message">
            <div class="header">
                Avisos detectados!
                <div class="sub header">Tem certeza que deseja vincular?</div>
            </div>
            <ul class="list">
                @foreach ($messages as $msg)
                <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>

        <form action="{{ $confirm }}" method="post" class="ui form">
            @csrf

            <input type="hidden" name="valor" id="valor" value="{{ $valor }}" />
            <input type="hidden" name="aviso" id="aviso" value="{{ join('; ', $messages) }}" />

            <div class="required field">
                <label id="justificativa">Justificativa</label>
                <textarea id="justificativa" name="justificativa" required autofocus></textarea>
              </div>
              <div class="ui buttons">
                  <button type="submit" class="ui red button">Vincular</button>
                  <a href="{{ $cancel }}" class="ui primary button">Cancelar</a>
              </div>
        </form>
</div>
@endsection
