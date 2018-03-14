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
              <li class="nav-item"><a class="nav-link" href="/vendors">Vendors</a></li>
              <li class="nav-item"><a class="nav-link" href="/licenses">Licenses</a></li>
            </ul>
            </nav>
        </header>
        <main role="main" class="container">
            @yield('content')
        </main>
        <script
              src="{{ mix('js/app.js') }}">
        </script>
    </body>
</html>
