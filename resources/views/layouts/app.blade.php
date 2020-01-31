<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <head>
        <title>{{ config('app.name', 'Book Manager') }}</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" type="image/png" href="/img/favicon.png">

        <link href="/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
        <link href="/css/site.css" type="text/css" rel="stylesheet" />
    </head>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <span><router-link to="/" class="navbar-brand">Book Manager</router-link></span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    @auth
                    <li class="nav-item">
                        <span><router-link to="/add" class="nav-link" id="add-menu">Add</router-link></span>
                    </li>
                    <li class="nav-item">
                        <span><router-link to="/book-manager" class="nav-link">Show All</router-link></span>
                    </li>
                    @endauth
                </ul>
            </div>
            <ul class="navbar-nav ml-auto">
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="/js/vue.min.js"></script>
    <script src="/js/vue-router.min.js"></script>
    <script src="/js/jquery.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/axios.min.js"></script>
    <script src="/js/rate.js"></script>
    <script src="/js/add-book.js"></script>
    <script src="/js/start.js"></script>
    <script src="/js/login.js"></script>
    <script src="/js/register.js"></script>
    <script src="/js/book-manager.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>
