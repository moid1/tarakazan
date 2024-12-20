@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <h4 class="mt-3">{{ __('messages.Edit Package') }}</h4> <!-- Translated Header -->

        <form method="POST" action="{{ route('admin.packages.update', $package->id) }}" class="row new-chemist-child-row">
            @csrf
            @method('PUT') <!-- Specify the PUT method for updating -->

            <div class="row">
                <!-- Package Name -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.Package Name') }}</div> <!-- Translated Label -->
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $package->name) }}" required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Number of Customers -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.Number of Customers') }}</div> <!-- Translated Label -->
                    <input type="number" name="customers" id="customers"
                        class="form-control @error('customers') is-invalid @enderror"
                        value="{{ old('customers', $package->customers) }}" required />
                    @error('customers')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.Price') }}</div> <!-- Translated Label -->
                    <input type="number" name="price" id="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $package->price) }}" step="0.01" required />
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- SMS Quantity -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.SMS Quantity') }}</div> <!-- Translated Label -->
                    <input type="number" name="quantity" id="quantity"
                        class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', $package->quantity) }}" required />
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12 text-end mt-3">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.packages.index') }}" class="chemist-cancel-btn me-2">{{ __('messages.Cancel') }}</a> <!-- Translated Cancel Button -->
                    <button class="chemist-add-btn" type="submit">{{ __('messages.Update Package') }}</button> <!-- Translated Submit Button -->
                </div>
            </div>
        </form>
    </div>
@endsection
