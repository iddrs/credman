<!DOCTYPE html>
<html lang="pt_br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title') | {{ config('app.name') }}</title>

    @vite(['resources/js/app.js', 'resources/fomantic/semantic.min.css', 'resources/fomantic/semantic.min.js'])

</head>

<body>

    @include('app.partials.navbar')

    <div style="margin-top: 5em"></div>

    {{-- <aside class="ui container"> --}}
    <aside>
        @yield('breadcrumb', '')
    </aside>

    {{-- <main class="ui container"><!-- main content --> --}}
    <main><!-- main content -->

        <article class="ui basic segment">

            @if ($errors->any())

                <div class="ui icon error message">
                    <i class="exclamation circle icon"></i>
                    <div class="content">
                        <div class="header">
                            Erros encontrados:
                        </div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>

            @endif

            @session('success')
                <div class="ui success message">{{ session('success') }}</div>
            @endsession

            @yield('content')
        </article>
    </main><!-- main content -->

    <br>
    <br>
    <br>

    <footer class="ui basic mini menu" style="position: fixed; bottom: 0; width: 100%;">
        <div class="item">Copyright © 2024 - <a href="https://everton3x.github.io">Everton da Rosa</a></div>
        <div class="right item">
            <a href="https://github.com/iddrs/credman">
                <i class="github icon"></i>
            </a>
        </div>
    </footer>

</body>

</html>
