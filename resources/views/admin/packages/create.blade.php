@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">
            Add New Package
        </h4>
        <form method="POST" action="{{ route('admin.packages.store') }}" class="row new-chemist-child-row">
            @csrf
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="chemist-input-heading">Package Name</div>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="chemist-input-heading">Number of Customers</div>
                        <input type="number" name="customers" id="customers"
                            class="form-control @error('customers') is-invalid @enderror" value="{{ old('customers') }}"
                            required />
                        @error('customers')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="chemist-input-heading">Price</div>
                        <input type="number" step="0.01" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                            required />
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                <div class="col-12">
                    <div class="chemist-input-heading">SMS Quantity</div>
                    <input type="number" name="quantity" id="quantity"
                        class="form-control @error('quantity') is-invalid @enderror" value="{{ old('price') }}" required />
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <!-- Submit Buttons -->
            <div class="col-12 add-chemist-btn-div mt-3 text-end">
                <button class="chemist-cancel-btn me-2" type="button">Cancel</button>
                <button class="chemist-add-btn" type="submit">Add Package</button>
            </div>

        </div>
    </div>
    </form>
    </div>
@endsection
