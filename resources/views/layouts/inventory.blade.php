<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Duwon Inventory Management</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.js') }}"></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

    <div class="container-fluid ps-0">
        <div class="row g-0">
            <nav class="col-2 text-light"
                style = "background: #03045E; min-height: 100vh;"> <!-- Left Side Nav -->

				<div>
                    <h1 class="h4 py-4 px-3 text-center">
                        Inventory Management
                    </h1>
                </div>
                <hr />

                <h5 class="text-center">Inventory</h5>
                <ul class="navbar-nav px-3 ms-auto mt-3 mb-2 mb-lg-0 fs-6">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/inventory/stockBalance') }}" >
                            Stock Balance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/inventory/stockIn') }}">Stock In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/inventory/stockOut') }}">
                            Stock Out
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="">
                            Stock Unit Convention
                        </a>
                    </li>
                </ul>
                <hr />

                <h5 class="text-center">Configuration</h5>
                <ul class="navbar-nav px-3 ms-auto mt-3 mb-2 mb-lg-0 fs-6">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="" >
                            Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="">Units</a>
                    </li>
                </ul>
                <hr />

                <h5 class="text-center">Admin Setting</h5>
                <ul class="navbar-nav px-3 ms-auto mt-3 mb-2 mb-lg-0 fs-6">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="" >
                            Change username
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="">Change password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </li>
                </ul>
			</nav> <!-- Left Side Nav -->


            <main class="col-10 mt-4">
                <div class="p-2">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>

    {{-- <footer class="text-center py-2 text-muted col-6 offset-3">
        &copy; Copyright 2022
    </footer> --}}


</body>
</html>
