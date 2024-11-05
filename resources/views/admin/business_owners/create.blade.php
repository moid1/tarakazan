@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">
            Add New Business
        </h4>

        <form method="POST" action="{{ route('admin.business.owner.store') }}" class="row new-chemist-child-row"
            enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="chemist-input-heading">Business Name</div>
                        <input type="text" name="business_name" id="business_name" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Business Email</div>
                        <input type="email" name="business_email" id="business_email" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Package</div>
                        <select name="package" id="package" required>
                            <option value="" disabled selected>Select Package</option>
                            @foreach ($packages as $package)
                                <option value={{ $package->id }}>{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Logo</div>
                        <input type="file" name="logo" id="logo" accept="image/*" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Slug</div>
                        <input type="text" name="slug" id="slug" required />
                    </div>
                    <!-- Password Field with Eye Icon -->
                    <div class="col-12 position-relative">
                        <div class="chemist-input-heading">Password</div>
                        <input type="password" name="password" id="password" required />
                        <i class="fas fa-eye" id="toggle-password"
                            style="position: absolute; right: 30px; top: 50%; cursor: pointer;"></i>
                    </div>
                    <!-- Generate Random Password Button -->
                    <div class="col-12 mt-2">
                        <button type="button" class="btn btn-secondary" id="generate-password">Generate Random
                            Password</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="chemist-input-heading">Address</div>
                        <input type="text" name="address" id="address" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Country</div>
                        <input type="text" name="country" id="country" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Postal Code</div>
                        <input type="text" name="postal_code" id="postal_code" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Social Media Links</div>
                        <input type="url" name="facebook" id="facebook" placeholder="Facebook URL" />
                        <input type="url" name="tiktok" id="tiktok" placeholder="TikTok URL" />
                        <input type="url" name="instagram" id="instagram" placeholder="Instagram URL" />
                    </div>
                    <div class="col-12 add-chemist-btn-div mt-3 text-end">
                        <button class="chemist-cancel-btn me-2" type="button">Cancel</button>
                        <button class="chemist-add-btn" type="submit">Add</button>
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

        // Function to generate a random password (same as before)
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
