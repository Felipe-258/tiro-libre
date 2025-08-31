<!DOCTYPE html>
<html>
<head>
    <title>Custom Auth in Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body class="body-verde">
    <nav class="nav-login navbar navbar-light navbar-expand-lg mb-5">
        <div class="container">
            <a href="{{ route('welcome') }}"><img class="logo" src="logo.png" alt="" height="60px"></a>
            <a class="titulo navbar-brand mr-auto text-white" href="{{ route('welcome') }}">Reserva Futbolera</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link text-white mr-auto" href="{{ route('login') }}">Acceso</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white mr-auto" href="{{ route('register-user') }}">Registro</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('signout') }}">Cerrar sesión</a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
</body>
</html>