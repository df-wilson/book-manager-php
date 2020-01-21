<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Book Manager</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="/img/favicon.png">
    <link href="/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link href="/css/site.css" type="text/css" rel="stylesheet" />
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
                <li class="nav-item">
                    <span><router-link to="/add" class="nav-link">Add</router-link></span>
                </li>
                <li class="nav-item">
                    <span><router-link to="/book-manager" class="nav-link">Show All</router-link></span>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span><router-link to="/login" class="nav-link" id="login-menu">Login</router-link></span>
            </li>
            <li class="nav-item">
                <span><router-link to="/register" class="nav-link" id="register-menu">Register</router-link></span>
            </li>

            <li class="nav-item">
                <span><router-link to="/logout" class="nav-link" id="logout-menu">Logout</router-link></span>
            </li>
        </ul>
    </nav>

    <router-view></router-view>
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
<script src="/js/logout.js"></script>
<script src="/js/register.js"></script>
<script src="/js/book-manager.js"></script>
<script src="/js/app.js"></script>
</body>
</html>