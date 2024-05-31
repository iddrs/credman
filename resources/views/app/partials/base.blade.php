<!DOCTYPE html>
<html lang="pt_br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title') | {{ config('app.name') }}</title>

    @vite(['resources/js/app.js', 'resources/css/charts.min.css', 'resources/fomantic/semantic.min.css', 'resources/fomantic/semantic.min.js'])

</head>

<body>

    @include('app.partials.navbar')

    <aside class="ui container">
        @yield('breadcrumb', '')
    </aside>

    <main class="ui container"><!-- main content -->

        <article class="ui segment">

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

</body>

</html>
