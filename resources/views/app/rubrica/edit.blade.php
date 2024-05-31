@extends('app.partials.base')

@section('title', "Editar Rubrica {$rubrica->acesso}/{$rubrica->exercicio} ({$rubrica->id})")

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="divider"> / </div>
        <a class="section" href="{{ route('rubricas', ['exercicio' => $rubrica->exercicio]) }}">Rubricas</a>
        <div class="divider"> / </div>
        <div class="active section">Editar</div>
    </div>
@endsection

@section('content')

<div class="ui segment">
    @include('app.partials.header', [
        'title' => "Rubrica {$rubrica->acesso}/{$rubrica->exercicio} ({$rubrica->id})",
        'subtitle' => 'Editar'
        ])
</div>


<div class="ui segment">
    @include('app.partials.form.rubrica', ['action' => route('rubrica.update'), 'title' => 'Edição', 'cancel' => route('rubricas', ['exercicio' => $rubrica->exercicio])])
</div>

@endsection
