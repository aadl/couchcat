<!doctype html>
<html lang="en">
    <head>
        <title>Couchcat - @yield('title')</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>
    <body>
        <header class="mb-3 fixed-top">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item"><a class="navbar-brand active" href="/">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="/vendor">Vendors</a></li>
              <li class="nav-item"><a class="nav-link" href="/license">Licenses</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li class="nav-item"><a href="{{ url('/login') }}" class="nav-link">Login</a></li>
                @else
                    <li class="nav-item navbar-text">{{ Auth::user()->name }} | <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a> <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form></li>
                @endif
            </ul>
            </nav>
        </header>
        <main role="main" class="container">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('status'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! session('status') !!}</li>
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
        <script
              src="{{ mix('js/app.js') }}">
        </script>
    </body>
</html>
