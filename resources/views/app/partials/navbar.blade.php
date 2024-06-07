<div class="ui top fixed menu"><!-- main menu -->
    <div class="header item">
        {{ config('app.name') }}
    </div>
    <a class="item" href="{{ route('dashboard') }}" accesskey="ctrl+1">
        Dashboard
    </a>
    <a class="item" href="{{ route('rubricas') }}" accesskey="ctrl+2">
        Rubricas
    </a>
    <a class="item" href="{{ route('leis') }}" accesskey="ctrl+3">
        Leis
    </a>
    <a class="item" href="{{ route('decretos') }}" accesskey="ctrl+4">
        Decretos
    </a>
    <div class="right menu">
        @if (auth()->check())
            <div class="item">
                <div class="ui buttons">
                    <a class="ui teal button" href="#">{{ \App\Support\Helpers\Fmt::userLabel(auth()->user()->name) }}</a>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="ui basic button">Sair</button>
                    </form>
                </div>
            </div>
        @else
            <div class="item">
                <div class="ui buttons">
                    <a href="{{ route('register') }}" class="ui teal basic button">Registrar</a>
                    <a href="{{ route('login') }}" class="ui teal button">Entrar</a>
                </div>
            </div>
        @endif
    </div>
</div><!-- main menu -->
