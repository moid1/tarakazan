@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="col-lg-12">
            <h4 class="mt-3">{{ __('messages.Edit Campaign SMS') }}</h4>

            <!-- Display Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display Error Message -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('campaign.sms.update', $campaignSms->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- This is needed to send a PUT request for updating -->

                <!-- Campaign Dropdown -->
                <div class="form-group mt-3">
                    <label for="campaign_id">{{ __('messages.Campaign') }}</label>
                    <select name="campaign_id" id="campaign_id" class="form-control @error('campaign_id') is-invalid @enderror" required>
                        <option value="">{{ __('messages.Select Campaign') }}</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" 
                                {{ old('campaign_id', $campaignSms->id) == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('campaign_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Message Field -->
                <div class="form-group mt-3">
                    <label for="message">{{ __('messages.Message') }}</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" placeholder="{{ __('messages.Enter your campaign message') }}" required>{{ old('message', $campaignSms->sms) }}</textarea>

                    @error('message')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Delivery Time -->
                <div class="form-group mt-3">
                    <label for="delivery_time">{{ __('messages.Delivery Time') }}</label>
                    <input type="datetime-local" class="form-control @error('delivery_time') is-invalid @enderror" id="delivery_time" name="delivery_time" required value="{{ old('delivery_time', $campaignSms->delivery_date) }}">

                    @error('delivery_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-group mt-5">
                    <button type="submit" class="chemist-add-btn">{{ __('messages.Update SMS Campaign') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        // Add any custom JS or datetime picker enhancements here, if needed
    </script>
@endsection
