@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">Update Business Owner</h4>

        <form method="POST" action="{{ route('admin.business.owner.update', $businessOwner->id) }}"
            class="row new-chemist-child-row" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- This specifies the PUT method for updating -->

            <div class="col-12">
                <div class="row">
                    <!-- Business Name -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Business Name</div>
                        <input type="text" name="business_name"
                            value="{{ old('business_name', $businessOwner->business_name) }}" required />
                        @error('business_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Google Place Id -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Google Place Id</div>
                        <input type="text" name="google_review"
                            value="{{ old('google_review', $businessOwner->google_review) }}" required />
                        @error('google_review')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SMS App Key -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">SMS App Key</div>
                        <input type="text" name="app_key" value="{{ old('app_key', $businessOwner->app_key) }}"
                            required />
                        @error('app_key')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SMS User Code -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">SMS User Code</div>
                        <input type="text" name="sms_user_code"
                            value="{{ old('sms_user_code', $businessOwner->sms_user_code) }}" required />
                        @error('sms_user_code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SMS User Password -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">SMS User Password</div>
                        <input type="text" name="sms_user_password"
                            value="{{ old('sms_user_password', $businessOwner->sms_user_password) }}" required />
                        @error('sms_user_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SMS Message Header -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">SMS Message Header</div>
                        <input type="text" name="sms_message_header"
                            value="{{ old('sms_message_header', $businessOwner->sms_message_header) }}" required />
                        @error('sms_message_header')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mersis No -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Mersis No</div>
                        <input type="text" name="mersis_no" value="{{ old('mersis_no', $businessOwner->mersis_no) }}"
                            required />
                        @error('mersis_no')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stop Link -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Stop Link</div>
                        <input type="text" name="stop_link" value="{{ old('stop_link', $businessOwner->stop_link) }}"
                            required />
                        @error('stop_link')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone No NetGSM -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Phone No NetGSM</div>
                        <input type="text" name="phone_number_netgsm"
                            value="{{ old('phone_number_netgsm', $businessOwner->phone_number_netgsm) }}" required />
                        @error('phone_number_netgsm')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Business Email -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Business Email</div>
                        <input type="email" name="business_email"
                            value="{{ old('business_email', $businessOwner->business_email) }}" required disabled/>
                        @error('business_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Package Selection -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Package</div>
                        <select name="package" required>
                            <option value="" disabled>Select Package</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ old('package', $businessOwner->package) == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('package')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Logo</div>
                        <input type="file" name="logo" accept="image/*" />
                        @if ($businessOwner->logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $businessOwner->logo) }}" alt="Business Logo"
                                    width="100">
                            </div>
                        @endif
                        @error('logo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Slug</div>
                        <input type="text" name="slug" value="{{ old('slug', $businessOwner->slug) }}" required />
                        @error('slug')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input and Icons -->
                    <div class="col-12 col-md-6 position-relative">
                        <div class="chemist-input-heading">Password</div>
                        <input type="password" name="password" id="password" />
                        <!-- Eye Icon -->
                        <i class="fas fa-eye" id="toggle-password"
                            style="position: absolute; right: 50px; top: 50%; cursor: pointer;"></i>
                        <!-- Generate Random Password Icon -->
                        <i class="fas fa-random" id="generate-password" 
                            style="position: absolute; right: 25px; top: 50%; cursor: pointer;" 
                            title="Generate Random Password"></i>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Address</div>
                        <input type="text" name="address" value="{{ old('address', $businessOwner->address) }}" required />
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Country</div>
                        <input type="text" name="country" value="{{ old('country', $businessOwner->country) }}" required />
                        @error('country')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Postal Code -->
                    <div class="col-12 col-md-6">
                        <div class="chemist-input-heading">Postal Code</div>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $businessOwner->postal_code) }}"
                            required />
                        @error('postal_code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Social Media Links -->
                    <div class="col-12">
                        <div class="chemist-input-heading">Social Media Links</div>
                        <input type="url" name="facebook" value="{{ old('facebook', $businessOwner->facebook) }}"
                            placeholder="Facebook URL" />
                        <input type="url" name="tiktok" value="{{ old('tiktok', $businessOwner->tiktok) }}"
                            placeholder="TikTok URL" />
                        <input type="url" name="instagram" value="{{ old('instagram', $businessOwner->instagram) }}"
                            placeholder="Instagram URL" />
                        @error('facebook')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('tiktok')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('instagram')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            @if ($businessOwner->qr_code_path)
                <div class="col-12">
                    <p>Scan the QR code to access your qrcode:</p>
                    <img src="{{ asset('storage/' . $businessOwner->qr_code_path) }}" alt="Chatbot QR Code">
                </div>
            @endif

            <!-- Responsive Action Buttons -->
            <div class="col-12 text-center mt-3">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.business.owner.index') }}" class="chemist-cancel-btn me-2">Cancel</a>
                    <button class="chemist-add-btn" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('customjs')
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
        document.getElementById("generate-password").addEventListener("click", function() {
            const randomPassword = generateRandomPassword();
            document.getElementById("password").value = randomPassword;
        });

        // Auto-generate the slug from the business name
        document.getElementById("business_name").addEventListener("input", function() {
            const slugField = document.getElementById("slug");
            slugField.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
        });
    </script>
@endsection
