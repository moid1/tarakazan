<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ asset('style.css') }}" />
    <script src="{{ asset('index.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container-fluid">
        <div class="row w-100 m-0">
            <div class="chemist-sidebar col-lg-3 d-none d-lg-block">
                <div class="row">
                    <span class="chemist-logo1 ">
                        <img src="{{ asset('images/tarakazan_logo.png') }}" alt="" class="img-fluid ">
                    </span>
                    <ul>
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">
                                <img class="me-2 ms-1" src="{{ asset('images/chemist dashboard.svg') }}"
                                    alt="" />Dashboard
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.business.owner.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.business.owner.index') }}">
                                <img class="me-2" src="{{ asset('images/chemist package box 06.svg') }}"
                                    alt="" />Business Owners
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">
                            <a href="{{ route('customers.index') }}">
                                <img class="me-2" src="{{ asset('images/chemist shopping bag.svg') }}"
                                    alt="" />Customers
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.packages.index') }}">
                                <img class="me-2" src="{{ asset('images/heart-pulse.svg') }}"
                                    alt="" />Packages
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <!-- Font Awesome Logout Icon -->
                                <i class="fas fa-sign-out-alt me-2"></i> <!-- Logout icon -->
                                {{ __('Logout') }}
                            </a>

                            <!-- Logout Form -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- ########################### For Small Screen Menu ######################### -->
            <div class="col-12 small-screen-nav d-lg-none ">
                <div class="row ">
                    <div class="col-12 hamburger-top  " style="z-index: 100;">
                        <div class="hamburger-container" onclick="toggleHamburger(this); toggleclick();">
                            <span onclick="toggleclick();" class="line1 line"></span>
                            <span onclick="toggleclick();" class="line2 line"></span>
                            <span onclick="toggleclick();" class="line3 line"></span>
                        </div>
                        <img class="small-screen-logo mx-auto" src="{{ asset('images/tarakazan_logo.png') }}"
                            alt="">
                    </div>

                    <div class="col-12 chemist-sidebar chemist-top-menubar">
                        <ul class="small-screen-ul">
                            <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                <a href="{{ route('home') }}">
                                    <img class="me-2 ms-1" src="{{ asset('images/chemist dashboard.svg') }}"
                                        alt="" />Dashboard
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.business.owner.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.business.owner.index') }}">
                                    <img class="me-2" src="{{ asset('images/chemist package box 06.svg') }}" alt="" />Business Owners
                                </a>
                            </li>
                            
                            <li class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">
                                <a href="{{ route('customers.index') }}">
                                    <img class="me-2" src="{{ asset('images/chemist shopping bag.svg') }}"
                                        alt="" />Customers
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.packages.index') }}">
                                    <img class="me-2" src="{{ asset('images/heart-pulse.svg') }}"
                                        alt="" />Packages
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                    <!-- Font Awesome Logout Icon -->
                                    <i class="fas fa-sign-out-alt me-2"></i> <!-- Logout icon -->
                                    {{ __('Logout') }}
                                </a>

                                <!-- Logout Form -->
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 dashboard-right mb-3">
                <div class="row ">
                    <div class="col-12 dashboard-top d-none d-lg-flex justify-content-end">

                        <div class="d-flex justify-content-center align-items-center">
                            <div class="d-flex flex-column">
                                <span class="smith">{{ Auth::user()->name }}</span>
                                <span>Admin</span>
                            </div>
                            <button class="dashboard-notification">
                                <i class="fas fa-bell"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 dashboard-morning align-items-md-center ">
                        <div class="smith-cont " style="margin-right: -1.3rem;">
                            <span class="smith-heading ">Good Morning, {{ Auth::user()->name }} <img
                                    src="{{ asset('images/hand_wave-removebg.png') }}" alt=""></span>
                            <div class="">Welcome, Manage your business</div>
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    @yield('customjs')
</body>

</html>
