@extends('layouts.owner')

@section('content')
    <div class="row edit-chemist-parent-row">
        <h4 class="mt-3">
            {{ __('messages.Edit Campaign') }}
        </h4>
        <form method="POST" action="{{ route('campaign.update', $campaign->id) }}" class="row edit-chemist-child-row">
            @csrf
            @method('PUT') <!-- This is required to indicate the form is updating -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="chemist-input-heading">{{ __('messages.Name') }}</div>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $campaign->name) }}" required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="chemist-input-heading">{{ __('messages.Description') }}</div>
                        <input type="text" name="description" id="description"
                            class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $campaign->description) }}"
                            required />
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <!-- Submit Buttons -->
                    <div class="col-12 add-chemist-btn-div mt-3 text-end">
                        <a href="{{ route('campaign.index') }}" class="chemist-cancel-btn me-2">{{ __('messages.Cancel') }}</a>
                        <button class="chemist-add-btn" type="submit">{{ __('messages.Update Campaign') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
