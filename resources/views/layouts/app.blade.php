<!doctype html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config("app.name", "Laravel") }} - @yield("title", "StageManager")</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Existing styles... */
        
        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>

    <!-- Scripts -->
    @vite(["resources/sass/app.scss", "resources/js/app.js"])

    <!-- Styles -->
    <style>
        /* Basic styling for demo */
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            margin-right: 10px;
        }
        .container {
            margin-top: 20px;
        }
        .alert {
            margin-top: 15px;
        }
        
        /* Custom logout button styling */
        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 0.25rem 1rem;
            color: #212529;
            text-decoration: none;
            display: block;
        }
        .logout-btn:hover {
            background-color: #e9ecef;
            color: #212529;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url("/") }}">
                    {{ config("app.name", "Laravel") }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __("Toggle navigation") }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->hasRole("Admin"))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("admin.dashboard") }}">Dashboard Admin</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("admin.users.index") }}">Utilisateurs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("admin.projects.index") }}">Projets</a>
                                </li>
                            @elseif(Auth::user()->hasRole("Encadrant"))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("encadrant.dashboard") }}">Dashboard Encadrant</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("encadrant.projects.index") }}">Mes Projets</a>
                                </li>
                            @elseif(Auth::user()->hasRole("Stagiaire"))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("stagiaire.dashboard") }}">Dashboard Stagiaire</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("stagiaire.projects.index") }}">Mes Projets</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("stagiaire.tasks.index") }}">Mes Tâches</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has("login"))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("login") }}">{{ __("Login") }}</a>
                                </li>
                            @endif

                            @if (Route::has("register"))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("register") }}">{{ __("Register") }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} ({{ Auth::user()->roles->pluck("name")->join(", ") }})
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- Fixed logout form - only this one -->
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item logout-btn">
                                            {{ __('Logout') }}
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session("success"))
                    <div class="alert alert-success" role="alert">
                        {{ session("success") }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield("content")
            </div>
        </main>
    </div>
    
    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts') <!-- This line is important for any page-specific scripts -->
</body>
</html>