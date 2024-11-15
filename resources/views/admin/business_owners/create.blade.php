@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">
            Add New Business
        </h4>

        <form method="POST" action="{{ route('admin.business.owner.store') }}" class="row new-chemist-child-row"
            enctype="multipart/form-data">
            @csrf
            <div class="col-12">
                <div class="row">
                    <!-- Business Name -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Business Name</div>
                        <input type="text" name="business_name" id="business_name" required class="form-control" />
                    </div>

                    <!-- Google Place Id -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Google Place Id</div>
                        <input type="text" name="google_review" id="google_review" required class="form-control" />
                    </div>

                    <!-- SMS App Key -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">SMS App Key</div>
                        <input type="text" name="app_key" id="app_key" required class="form-control" />
                    </div>

                    <!-- Business Email -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Business Email</div>
                        <input type="email" name="business_email" id="business_email" required class="form-control" />
                    </div>

                    <!-- Package Selection -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Package</div>
                        <select name="package" id="package" required class="form-control">
                            <option value="" disabled selected>Select Package</option>
                            @foreach ($packages as $package)
                                <option value={{ $package->id }}>{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Logo Upload -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Logo</div>
                        <input type="file" name="logo" id="logo" accept="image/*" class="form-control" />
                    </div>

                    <!-- Slug -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Slug</div>
                        <input type="text" name="slug" id="slug" required class="form-control" />
                    </div>

                    <!-- Password Field with Eye Icon and Random Icon -->
                    <div class="col-12 col-md-6 position-relative mb-3">
                        <div class="chemist-input-heading">Password</div>
                        <input type="password" name="password" id="password" required class="form-control" />
                        <i class="fas fa-eye" id="toggle-password"
                            style="position: absolute; right: 50px; top: 50%; cursor: pointer;"></i>
                        <i class="fas fa-random" id="generate-password-icon"
                            style="position: absolute; right: 25px; top: 50%; cursor: pointer;"></i>
                    </div>

                    <!-- Address -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Address</div>
                        <input type="text" name="address" id="address" required class="form-control" />
                    </div>

                    <!-- Country -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Country</div>
                        <input type="text" name="country" id="country" required class="form-control" />
                    </div>

                    <!-- Postal Code -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Postal Code</div>
                        <input type="text" name="postal_code" id="postal_code" required class="form-control" />
                    </div>

                    <!-- Social Media Links -->
                    <div class="col-12 mb-3">
                        <div class="chemist-input-heading">Social Media Links</div>
                        <input type="url" name="facebook" id="facebook" placeholder="Facebook URL"
                            class="form-control mb-2" />
                        <input type="url" name="tiktok" id="tiktok" placeholder="TikTok URL"
                            class="form-control mb-2" />
                        <input type="url" name="instagram" id="instagram" placeholder="Instagram URL"
                            class="form-control" />
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 text-end mt-3">
                        <div class="d-flex justify-content-end">
                            <button class="chemist-cancel-btn me-2" type="button">Cancel</button>
                            <button class="chemist-add-btn" type="submit">Add</button>
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

        document.getElementById("business_name").addEventListener("input", function() {
            const slugField = document.getElementById("slug");
            slugField.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
        });
    </script>
@endsection
