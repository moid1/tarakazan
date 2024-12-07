@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">
            {{ __('messages.Add New Coupon') }}
        </h4>
        <form method="POST" action="{{ route('coupon.store') }}" class="row new-chemist-child-row">
            @csrf
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="chemist-input-heading">{{ __('messages.Coupon Code') }}</div>
                        <input type="text" name="code" id="code"
                            class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required />
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="chemist-input-heading">{{ __('messages.Expiry Date') }}</div>
                        <input type="datetime-local" name="expiry_date" id="expiry_date"
                            class="form-control @error('expiry_date') is-invalid @enderror" 
                            value="{{ old('expiry_date') }}" required />
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    

                    <div class="col-12">
                        <div class="chemist-input-heading">{{ __('messages.Gift Description') }}</div>
                        <input type="text" name="gift" id="gift"
                            class="form-control @error('gift') is-invalid @enderror" value="{{ old('gift') }}" required />
                        @error('gift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <!-- Submit Buttons -->
                    <div class="col-12 add-chemist-btn-div mt-3 text-end">
                        <button class="chemist-cancel-btn me-2" type="button">{{ __('messages.Cancel') }}</button>
                        <button class="chemist-add-btn" type="submit">{{ __('messages.Add Coupon') }}</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
