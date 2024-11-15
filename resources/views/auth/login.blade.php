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

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Make sure the html and body take up full height */
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 900px;
            width: 100%;
        }

        .card {
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 2rem;
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
    </style>
</head>

<body>
    <div id="app">
        <div class="container">
            <!-- Login Section -->
            <section class="p-3 p-md-4 p-xl-5">
                <div class="card border-light-subtle shadow-sm">
                    <div class="row g-0">
                        <div class="col-12 col-md-6 text-bg-dark">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="col-10 col-xl-8 py-3 text-center">
                                    <img class="img-fluid rounded mb-4" loading="lazy"
                                        src="{{ asset('images/light_logo.png') }}" width="300" height="80"
                                        alt="Tarakazan Logo">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card-body p-3 p-md-4 p-xl-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-5">
                                            <h3>Log in</h3>
                                        </div>
                                    </div>
                                </div>

                                <!-- Login Form -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row gy-3 gy-md-4 overflow-hidden">
                                        <!-- Email Field -->
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" id="email" placeholder="name@example.com"
                                                value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password Field -->
                                        <div class="col-12">
                                            <label for="password" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="password" value="{{ old('password') }}" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button class="btn bsb-btn-xl btn-primary" type="submit">Log in now</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Forgot Password Link -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <a href="{{ route('password.request') }}" class="link-secondary">
                                            Forgot password?
                                        </a>
                                    </div>
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
