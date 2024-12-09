@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        <h4 class="mt-3">{{ __('messages.Update Business Profile') }}</h4>

        <!-- Form to update the business profile -->
        <form method="POST" action="{{ route('business-owner.update-profile') }}" class="row  new-chemist-child-row"
            enctype="multipart/form-data">
            @csrf
            @method('POST') <!-- Using POST method, will be handled by the controller -->

            <div class="col-md-12">
                <div class="row">
                    <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.Business Name') }}</div>
                        <input type="text" name="business_name" id="business_name"
                            value="{{ old('business_name', $businessOwner->business_name) }}" required />
                        @error('business_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.Google Place Id') }}</div>
                        <input readonly type="text" name="google_review" id="google_review"
                            value="{{ old('google_review', $businessOwner->google_review) }}" required />
                        @error('google_review')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.SMS App Key') }}</div>
                        <input type="text" name="app_key" id="app_key"
                            value="{{ old('app_key', $businessOwner->app_key) }}" required />
                        @error('app_key')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.SMS User Code') }}</div>
                        <input type="text" name="sms_user_code" id="sms_user_code"
                            value="{{ old('sms_user_code', $businessOwner->sms_user_code) }}" required />
                        @error('sms_user_code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.SMS User Password') }}</div>
                        <input type="text" name="sms_user_password" id="sms_user_password"
                            value="{{ old('sms_user_password', $businessOwner->sms_user_password) }}" required />
                        @error('sms_user_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.SMS Message Header') }}</div>
                        <input type="text" name="sms_message_header" id="sms_message_header"
                            value="{{ old('sms_message_header', $businessOwner->sms_message_header) }}" required />
                        @error('sms_message_header')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.Business Email') }}</div>
                        <input type="email" name="business_email" id="business_email"
                            value="{{ old('business_email', $businessOwner->business_email) }}" required />
                        @error('business_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.Logo') }}</div>
                        <input type="file" name="logo" id="logo" accept="image/*" />
                        @if ($businessOwner->logo)
                            <img src="{{ asset('storage/' . $businessOwner->logo) }}" alt="Business Logo"
                                class="img-thumbnail mt-2" width="100">
                        @endif
                    </div> --}}

                    {{-- <div class="col-6">
                        <div class="chemist-input-heading">{{ __('messages.Slug') }}</div>
                        <input readonly type="text" name="slug" id="slug"
                            value="{{ old('slug', $businessOwner->slug) }}" required />
                        @error('slug')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <!-- Password Field with Eye Icon -->
                    <div class="col-6 position-relative">
                        <div class="chemist-input-heading">{{ __('messages.Password') }}</div>
                        <input type="password" name="password" id="password" value="{{ old('password') }}" />
                        <i class="fas fa-eye" id="toggle-password"
                            style="position: absolute; right: 30px; top: 50%; cursor: pointer;"></i>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Generate Random Password Button -->
                    <div class="col-6 mt-2 mb-3">
                        <button type="button" class="btn btn-secondary"
                            id="generate-password">{{ __('messages.Generate Random Password') }}</button>
                    </div>
                </div>
            </div>

            <!-- Address, Country, and Postal Code Fields -->
            <div class="col-6">
                <div class="profile-input-heading">{{ __('messages.Address') }}</div>
                <input type="text" name="address" id="address" value="{{ old('address', $businessOwner->address) }}"
                    required />
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <div class="profile-input-heading">{{ __('messages.Country') }}</div>
                <input type="text" name="country" id="country" value="{{ old('country', $businessOwner->country) }}"
                    required />
                @error('country')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <div class="profile-input-heading">{{ __('messages.Postal Code') }}</div>
                <input type="text" name="postal_code" id="postal_code"
                    value="{{ old('postal_code', $businessOwner->postal_code) }}" required />
                @error('postal_code')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Social Media Links -->
            <div class="col-6">
                <div class="profile-input-heading">{{ __('messages.Social Media Links') }}</div>
                <input type="url" name="facebook" id="facebook" placeholder="{{ __('messages.Facebook URL') }}"
                    value="{{ old('facebook', $businessOwner->facebook) }}" />
                <input type="url" name="instagram" id="instagram" placeholder="{{ __('messages.Instagram URL') }}"
                    value="{{ old('instagram', $businessOwner->instagram) }}" />
                <input type="url" name="tiktok" id="tiktok" placeholder="{{ __('messages.TikTok URL') }}"
                    value="{{ old('tiktok', $businessOwner->tiktok) }}" />
            </div>

            <div class="col-6">
                @if ($businessOwner->qr_code_path)
                    <div>
                        <p>{{ __('messages.Scan the QR code to access your chatbot:') }}</p>
                        <img src="{{ asset('storage/' . $businessOwner->qr_code_path) }}" alt="Chatbot QR Code">
                    </div>
                @endif
            </div>

            <!-- Buttons for Cancel and Save -->
            <div class="col-12 text-end mt-3">
                @if ($businessOwner->qr_code_path)
                    <a href="{{ asset('storage/' . $businessOwner->qr_code_path) }}"
                        download="{{ $businessOwner->slug }}-qr-code.png"
                        class="btn btn-success me-2">{{ __('messages.Download QR Code') }}</a>
                @endif
                <button class="btn btn-secondary me-2" type="button"
                    onclick="window.history.back();">{{ __('messages.Cancel') }}</button>
                <button class="btn btn-primary" type="submit">{{ __('messages.Save Changes') }}</button>
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

        // When the "Generate Random Password" button is clicked
        document.getElementById("generate-password").addEventListener("click", function() {
            const randomPassword = generateRandomPassword();
            document.getElementById("password").value = randomPassword;
        });
    </script>
@endsection
