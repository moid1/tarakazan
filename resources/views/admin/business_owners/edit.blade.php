@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">Update Business Owner</h4>

        <form method="POST" action="{{ route('admin.business.owner.update', $businessOwner->id) }}"
            class="row new-chemist-child-row" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- This specifies the PUT method for updating -->

            <div class="col-md-12">
                <div class="row">
                    <div class="col-6">
                        <div class="chemist-input-heading">Business Name</div>
                        <input type="text" name="business_name"
                            value="{{ old('business_name', $businessOwner->business_name) }}" required />
                    </div>
                    <div class="col-6">
                        <div class="chemist-input-heading">Google Place Id</div>
                        <input type="text" name="google_review"
                            value="{{ old('google_review', $businessOwner->google_review) }}" required />
                    </div>
                    <div class="col-6">
                        <div class="chemist-input-heading">SMS App Key</div>
                        <input type="text" name="app_key" value="{{ old('app_key', $businessOwner->app_key) }}"
                            required />
                    </div>
                    <div class="col-6">
                        <div class="chemist-input-heading">Business Email</div>
                        <input type="email" name="business_email"
                            value="{{ old('business_email', $businessOwner->business_email) }}" required />
                    </div>
                    <div class="col-6">
                        <div class="chemist-input-heading">Package</div>
                        <select name="package" required>
                            <option value="" disabled>Select Package</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ $businessOwner->package == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="chemist-input-heading">Logo</div>
                        <input type="file" name="logo" accept="image/*" />
                        @if ($businessOwner->logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $businessOwner->logo) }}" alt="Business Logo"
                                    width="100">
                            </div>
                        @endif
                    </div>
                    <div class="col-6">
                        <div class="chemist-input-heading">Slug</div>
                        <input type="text" name="slug" value="{{ old('slug', $businessOwner->slug) }}" required />
                    </div>

                    <div class="col-6 position-relative">
                        <div class="chemist-input-heading">Password</div>
                        <input type="password" name="password" />
                        <i class="fas fa-eye" id="toggle-password"
                            style="position: absolute; right: 30px; top: 50%; cursor: pointer;"></i>
                    </div>

                    <div class="col-6 mt-2 mb-3">
                        <button type="button" class="btn btn-secondary" id="generate-password">Generate Random
                            Password</button>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="chemist-input-heading">Address</div>
                <input type="text" name="address" value="{{ old('address', $businessOwner->address) }}" required />
            </div>
            <div class="col-6">
                <div class="chemist-input-heading">Country</div>
                <input type="text" name="country" value="{{ old('country', $businessOwner->country) }}" required />
            </div>
            <div class="col-6">
                <div class="chemist-input-heading">Postal Code</div>
                <input type="text" name="postal_code" value="{{ old('postal_code', $businessOwner->postal_code) }}"
                    required />
            </div>
            <div class="col-6">
                <div class="chemist-input-heading">Social Media Links</div>
                <input type="url" name="facebook" value="{{ old('facebook', $businessOwner->facebook) }}"
                    placeholder="Facebook URL" />
                <input type="url" name="tiktok" value="{{ old('tiktok', $businessOwner->tiktok) }}"
                    placeholder="TikTok URL" />
                <input type="url" name="instagram" value="{{ old('instagram', $businessOwner->instagram) }}"
                    placeholder="Instagram URL" />
            </div>

            @if ($businessOwner->qr_code_path)
                <div>
                    <p>Scan the QR code to access your chatbot:</p>
                    <img src="{{ asset('storage/' . $businessOwner->qr_code_path) }}" alt="Chatbot QR Code">
                </div>
            @endif

            <div class="col-12 text-end add-chemist-btn-div mt-3">
                <button class="chemist-cancel-btn me-2" type="button">Cancel</button>
                <button class="chemist-add-btn" type="submit">Update</button>
            </div>
        </form>
    </div>
@endsection
