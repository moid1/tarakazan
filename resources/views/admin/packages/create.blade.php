@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">{{ __('messages.Add New Package') }}</h4>  <!-- Translated title -->
        <form method="POST" action="{{ route('admin.packages.store') }}" class="row new-chemist-child-row">
            @csrf
            <div class="row">
                <!-- Package Name -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.Package Name') }}</div>  <!-- Translated label -->
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Number of Customers -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.Number of Customers') }}</div>  <!-- Translated label -->
                    <input type="number" name="customers" id="customers"
                        class="form-control @error('customers') is-invalid @enderror" value="{{ old('customers') }}"
                        required />
                    @error('customers')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.Price') }}</div>  <!-- Translated label -->
                    <input type="number" step="0.01" name="price" id="price"
                        class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required />
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- SMS Quantity -->
                <div class="col-12 col-md-6">
                    <div class="chemist-input-heading">{{ __('messages.SMS Quantity') }}</div>  <!-- Translated label -->
                    <input type="number" name="quantity" id="quantity"
                        class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}"
                        required />
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12 text-end mt-3">
                <div class="d-flex justify-content-end">
                    <button class="chemist-cancel-btn me-2" type="button">{{ __('messages.Cancel') }}</button>  <!-- Translated button -->
                    <button class="chemist-add-btn" type="submit">{{ __('messages.Add') }}</button>  <!-- Translated button -->
                </div>
            </div>
        </form>
    </div>
@endsection
