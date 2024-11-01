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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container-fluid">
        <div class="row w-100 m-0">
            <div class="chemist-sidebar col-lg-3 d-none d-lg-block">
                {{-- <button id="toggle-sidebar" class="toggle-btn">☰</button> --}}
                <div class="row">
                    <span class="chemist-logo1 ">
                        <img src="{{ asset('images/tarakazan_logo.png') }}" alt="" class="img-fluid ">
                    </span>
                    <ul>
                        <li class="active">
                            <a href="../Admin Portal/adminDashboard.html"><img class="me-2 ms-1"
                                    src="../images/chemist dashboard.svg" alt="" />Dashboard</a>
                        </li>
                        <li>
                            <a href="../Admin Portal/adminProducts.html"><img class="me-2"
                                    src="../images/chemist package box 06.svg" alt="" />Business Owners</a>
                        </li>
                        <li>
                            <a href="../Admin Portal/adminOrders.html"><img class="me-2"
                                    src="../images/chemist shopping bag.svg" alt="" />Customers</a>
                        </li>
                        <li>
                            <a href="../Admin Portal/adminChemists.html"><img class="me-2"
                                    src="../images/heart-pulse.svg" alt="" />Packages</a>
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
                        <img class="small-screen-logo mx-auto" src="{{ asset('images/tarakazan_logo.png') }}" alt="">
                    </div>

                    <div class="col-12 chemist-sidebar chemist-top-menubar">
                        <ul class="small-screen-ul">
                            <li class="active">
                                <a href="../Admin Portal/adminDashboard.html"><img class="me-2 ms-1 "
                                        src="{{ asset('images/chemist dashboard.svg') }}" alt="" />Dashboard</a>
                            </li>
                            <li>
                                <a href="../Admin Portal/adminProducts.html"><img class="me-2"
                                        src="../images/chemist package box 06.svg" alt="" />Products</a>
                            </li>
                            <li>
                                <a href="../Admin Portal/adminOrders.html"><img class="me-2"
                                        src="../images/chemist shopping bag.svg" alt="" />Orders</a>
                            </li>
                            <li>
                                <a href="../Admin Portal/adminChemists.html"><img class="me-2"
                                        src="../images/heart-pulse.svg" alt="" />Chemists</a>
                            </li>
                            <li>
                                <a href="../Admin Portal/adminCustomers.html"><img class="me-2"
                                        src="../images/chemist users 01.svg" alt="" />Customers</a>
                            </li>
                            <li>
                                <a href="../Admin Portal/adminPayment.html"><img class="me-2"
                                        src="../images/coin-dollar.svg" alt="" />Payments</a>
                            </li>
                            <li>
                                <a href="../Admin Portal/adminSettings.html"><img class="me-2"
                                        src="../images/chemist setting.svg" alt="" />Settings</a>
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
                                    src="../images/hand_wave-removebg.png" alt=""></span>
                            <div class="welcome-order mt-3">Welcome, Manage your business</div>
                        </div>
                    </div>


                    <div class="row dashboard-detail ">
                        <div class="col-4">
                            <div class=" detail-container1">
                                <img class="ms-3 mt-3" src="../images/dash detail 1.svg" alt="" />
                                <span class="detail-h1">Total Active Businesses</span>
                                <div class="detail-h2">49</div>
                                <div style="border-top: 1px solid #ededed">
                                    <span class="detail-h3">Update: October 20, 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class=" detail-container1">
                                <img class="ms-3 mt-3" src="../images/dash detail 2.svg" alt="" />
                                <span class="detail-h1">Total Customer Interactions</span>
                                <div class="detail-h2">560</div>

                                <div style="border-top: 1px solid #ededed">
                                    <span class="detail-h3">Update: October 20, 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class=" detail-container1">
                                <img class="ms-3 mt-3" src="../images/dash detail 3.svg" alt="" />
                                <span class="detail-h1">Total Revenue</span>
                                <div class="detail-h2">10589</div>

                                <div style="border-top: 1px solid #ededed">
                                    <span class="detail-h3">Update: October 20, 2023</span>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- ############################ CHARTS ############################  -->

                    <div class="chart-container col-12 mt-3">
                        <div class="chart-box1 ">
                            <div class="chart-box ">
                                <div class="chart-btn-div">
                                    <span class="chart-heading">Total Customers</span>
                                    {{-- <button>This Month <img class="ms-2" src="../images/Vector 1755.svg"
                                            alt=""></button> --}}
                                </div>
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                        <div class="chart-box2">
                            <div class="chart-box ">
                                <div class="chart-btn-div">
                                    <span class="chart-heading">Local Business Owners</span>
                                    {{-- <button>This Month <img class="ms-2" src="../images/Vector 1755.svg"
                                            alt=""></button> --}}
                                </div>
                                <canvas id="orderChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>