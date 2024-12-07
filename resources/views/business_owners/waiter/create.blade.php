@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
            <!-- Display Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        <h4 class="mt-3">
            {{ __('messages.Add New Waiter') }} <!-- Translated title -->
        </h4>

        <form method="POST" action="{{ route('waiter.store') }}" class="row new-chemist-child-row">
            @csrf
            <div class="col-12">
                <div class="row">
                    <!-- Business Name -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">{{ __('messages.Name') }}</div>
                        <input type="text" name="name" id="name" required class="form-control" />
                    </div>

                    <!-- Business Email -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">{{ __('messages.Email') }}</div> <!-- Translated label -->
                        <input type="email" name="email" id="email" required class="form-control" />
                    </div>

                    <!-- Password Field with Eye Icon and Random Icon -->
                    <div class="col-12 col-md-6 position-relative mb-3">
                        <div class="chemist-input-heading">{{ __('messages.Password') }}</div> <!-- Translated label -->
                        <input type="password" name="password" id="password" required class="form-control" />
                        <i class="fas fa-eye" id="toggle-password"
                            style="position: absolute; right: 50px; top: 50%; cursor: pointer;"></i>
                        <i class="fas fa-random" id="generate-password-icon"
                            style="position: absolute; right: 25px; top: 50%; cursor: pointer;"></i>
                    </div>


                    <!-- Buttons -->
                    <div class="col-12 text-end mt-3">
                        <div class="d-flex justify-content-end">
                            <button class="chemist-cancel-btn me-2" type="button">{{ __('messages.Cancel') }}</button>
                            <!-- Translated -->
                            <button class="chemist-add-btn" type="submit">{{ __('messages.Add') }}</button>
                            <!-- Translated -->
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <script>
        // Function to toggle password visibility
        document.getElementById("toggle-password").addEventListener("click", function() {
            const passwordField = document.getElementById("password");
            const passwordType = passwordField.type === "password" ? "text" : "password";
            passwordField.type = passwordType;

            // Toggle eye icon (change between 'fa-eye' and 'fa-eye-slash')
            this.classList.toggle("fa-eye-slash");
        });

        // Function to generate a random password
        function generateRandomPassword(length = 12) {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=<>?";
            let password = "";
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }
            return password;
        }

        // When the "Generate Random Password" icon is clicked
        document.getElementById("generate-password-icon").addEventListener("click", function() {
            const randomPassword = generateRandomPassword();
            document.getElementById("password").value = randomPassword;
        });
    </script>
@endsection
