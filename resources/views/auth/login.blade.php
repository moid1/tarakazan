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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Make sure the html and body take up full height */
        html,
        body {
            margin: 0;
            align-items: center;
            justify-content: center;
            background-color: #f7f7f7;
        }

       

        .card {
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .text-bg-dark {
            background-color: #343a40;
            color: white;
        }

        .btn-primary {
            background-color: #fa8502;
            border: none;
        }

        .btn-primary:hover {
            background-color: #e47800;
        }

        .form-check-label {
            color: #6c757d;
        }

        .link-secondary {
            color: #007bff;
        }

        .link-secondary:hover {
            text-decoration: underline;
        }

        /* Background image container styles */
        .bg-image-container {
            position: relative;
            background-image: url('{{ asset('login.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* background-color: radial-gradient(circle, rgba(240,128,170,1) 0%, rgba(255,150,1,1) 40%); */
        }

        /* Gradient overlay on top of the background image */
        .bg-image-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: radial-gradient(circle, rgba(240,128,170,1) 0%, rgba(255,150,1,1) 40%);
        }

        /* Optional: For improving the text contrast and visibility */
        .bg-image-container h2, .bg-image-container p {
            color: white;
        }

        .bg-image-container p {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Mobile responsiveness */
        @media (max-width: 767px) {
            .bg-image-container {
                display: none; /* Hide background on small screens */
            }

            .card-body {
                padding: 1rem; /* Adjust padding for smaller screens */
            }

            .card {
                box-shadow: none; /* Remove shadow for mobile */
            }
        }

        /* Desktop layout */
        @media (min-width: 768px) {
            .bg-image-container {
                display: block; /* Make sure the background image shows on larger screens */
            }
        }

         /* Styling the icons inside the input fields */
         .input-group {
            position: relative;
        }

        .input-group input {
            padding-left: 2.5rem; /* Add padding to the left for the icon */
        }

        .input-group .input-icon {
            position: absolute;
            left: 10px;
            z-index: 1;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            color: #6c757d; /* Icon color */
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="container" style="display: flex;justify-content:center;align-items-center;margin-top:70px">
            <!-- Login Section -->
            <section class="p-3 p-md-4 p-xl-5">
                <div class=" border-light-subtle shadow-sm">
                    <div class="row g-0">
                        <!-- Left Column with Login Form -->
                        <div class="col-12 col-md-6">
                            <div class=" p-3 p-md-4 p-xl-5">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <img class="text-center img-fluid rounded mb-4" loading="lazy"
                                            src="{{ asset('images/tarakazan_logo.png') }}" width="300" height="80"
                                            alt="Tarakazan Logo">
                                        <div class="mb-5 text-center">
                                            <h4>Hello!</h4>
                                            <h3>{{ __('messages.Sign in to your account') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <!-- Login Form -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row gy-3 gy-md-4 overflow-hidden">
                                        <!-- Email Field -->
                                        <div class="col-12">
                                            <label for="email" class="form-label">{{ __('messages.Email') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    id="email" placeholder="name@example.com"
                                                    value="{{ old('email') }}" required>
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password Field -->
                                        <div class="col-12">
                                            <label for="password" class="form-label">{{ __('messages.Password') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" placeholder="password" id="password" value="{{ old('password') }}" required>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button class="btn bsb-btn-xl btn-primary"
                                                    type="submit">{{ __('messages.Log in now') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Forgot Password Link -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <a href="{{ route('password.request') }}" class="link-secondary">
                                            {{ __('messages.Forgot password?') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column with Background Image -->
                        <div class="col-12 col-md-6 bg-image-container">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="col-10 col-xl-8 py-3 text-center">
                                    <!-- Add any content you want on the right side here -->
                                    {{-- <h2 class="text-white">Welcome to the Dashboard</h2>
                                    <p class="text-white-50">Login to continue</p> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>
