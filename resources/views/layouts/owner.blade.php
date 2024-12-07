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
                    <span class="chemist-logo1">
                        <img src="{{ asset('images/tarakazan_logo.png') }}" alt="" class="img-fluid ">
                    </span>
                    <ul>
                        <!-- Dashboard -->
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">
                                <i class="fas fa-tachometer-alt me-2 ms-1"></i> {{ __('messages.Dashboard') }}
                            </a>
                        </li>

                        <!-- Campaigns -->
                        <li class="{{ request()->routeIs('campaign.index') ? 'active' : '' }}">
                            <a href="{{ route('campaign.index') }}">
                                <i class="fas fa-bullhorn me-2"></i> {{ __('messages.Campaigns') }}
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('campaign.sms.*') ? 'active' : '' }}">
                            <a href="{{ route('campaign.sms.index') }}">
                                <i class="fas fa-sms me-2"></i> {{ __('messages.Schedule SMS') }}
                            </a>
                        </li>

                        <!-- Social Media Insights -->
                        {{-- <li class="{{ request()->routeIs('social-media.index') ? 'active' : '' }}">
                            <a href="{{ route('social-media.index') }}">
                                <i class="fas fa-chart-line me-2"></i> {{ __('messages.Social Media Insights') }}
                            </a>
                        </li> --}}

                        <li class="{{ request()->routeIs('waiter*') ? 'active' : '' }}">
                            <a href="{{ route('waiter.index') }}">
                                <i class="fas fa-user me-2"></i> {{ __('messages.Waiter') }}
                            </a>
                        </li>


                        <!-- Gift Management -->
                        <li class="{{ request()->routeIs('coupon.index') ? 'active' : '' }}">
                            <a href="{{ route('coupon.index') }}">
                                <i class="fas fa-gift me-2"></i> {{ __('messages.Gift Management') }}
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('business-owner.profile') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('business-owner.profile') }}">
                                <i class="fas fa-user me-2"></i> {{ __('messages.Profile') }}
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.Logout') }}
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
            <div class="col-12 small-screen-nav d-lg-none">
                <div class="row ">
                    <div class="col-12 hamburger-top" style="z-index: 100;">
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
                            <!-- Dashboard -->
                            <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-tachometer-alt me-2 ms-1"></i> {{ __('messages.Dashboard') }}
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('campaign.index') ? 'active' : '' }}">
                                <a href="{{ route('campaign.index') }}">
                                    <i class="fas fa-bullhorn me-2"></i> {{ __('messages.Campaigns') }}
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('campaign.sms.*') ? 'active' : '' }}">
                                <a href="{{ route('campaign.sms.index') }}">
                                    <i class="fas fa-sms me-2"></i> {{ __('messages.Schedule SMS') }}
                                </a>
                            </li>
                            {{-- <li class="{{ request()->routeIs('social-media.index') ? 'active' : '' }}">
                                <a href="{{ route('social-media.index') }}">
                                    <i class="fas fa-chart-line me-2"></i> {{ __('messages.Social Media Insights') }}
                                </a>
                            </li> --}}
                            <li class="{{ request()->routeIs('waiter*') ? 'active' : '' }}">
                                <a href="{{ route('waiter.index') }}">
                                    <i class="fas fa-user me-2"></i> {{ __('messages.Waiter') }}
                                </a>
                            </li>
                            <!-- Gift Management -->
                            <li class="{{ request()->routeIs('coupon.index') ? 'active' : '' }}">
                                <a href="{{ route('coupon.index') }}">
                                    <i class="fas fa-gift me-2"></i> {{ __('messages.Gift Management') }}
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('business-owner.profile') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('business-owner.profile') }}">
                                    <i class="fas fa-user me-2"></i> {{ __('messages.Profile') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.Logout') }}
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
                    <div class="col-12 dashboard-top d-flex justify-content-between align-items-center flex-wrap">
                        <!-- Language Selector Dropdown (select option) -->
                        <div class="d-flex flex-column flex-sm-row align-items-center mb-3 mb-sm-0">
                            <form id="language-form" action="{{ route('change.lang', ['lang' => 'en']) }}"
                                method="GET" class="form-inline">
                                @csrf
                                <select name="lang" class="form-control" onchange="changeLanguage(this)">
                                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="tr" {{ app()->getLocale() == 'tr' ? 'selected' : '' }}>Türkçe
                                    </option>
                                </select>
                            </form>
                        </div>

                        <!-- User Info (Business Owner Name and Role) -->
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="d-flex flex-column text-center text-sm-start">
                                <span class="smith">{{ Auth::user()->name }}</span>
                                <span>{{ __('messages.Business Owner') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 dashboard-morning align-items-md-center ">
                        <div class="smith-cont" style="margin-right: -1.3rem;">
                            <span class="smith-heading">{{ __('messages.Hey') }}, {{ Auth::user()->name }} <img
                                    src="{{ asset('images/hand_wave-removebg.png') }}" alt=""></span>
                            <div class="welcome-order mt-3">{{ __('messages.Welcome, Manage your business') }}</div>
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
    <script>
        function changeLanguage(select) {
            var form = document.getElementById('language-form');
            form.submit();
        }
    </script>
</body>

</html>
