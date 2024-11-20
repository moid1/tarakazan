@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="col-lg-12">
            <h4 class="mt-3">Send Campaign SMS</h4>

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

            <form action="{{ route('campaign.sms.store') }}" method="POST">
                @csrf

                

                <div class="form-group mt-3">
                    <label for="message">Campaign</label>
                    <select name="campaign_id" id="" class="form-control">
                        @foreach ($campaigns as $campaign)
                            <option value="{{$campaign->id}}">{{$campaign->name}}</option>
                        @endforeach
                    </select>
                </div>



                <!-- Message Field -->
                <div class="form-group mt-3">
                    <label for="message">Message</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4"
                        placeholder="Enter your campaign message" required>{{ old('message') }}</textarea>

                    @error('message')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Delivery Time -->
                <div class="form-group mt-3">
                    <label for="delivery_time">Delivery Time</label>
                    <input type="datetime-local" class="form-control @error('delivery_time') is-invalid @enderror"
                        id="delivery_time" name="delivery_time" required value="{{ old('delivery_time') }}">

                    @error('delivery_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mt-5">
                    <button type="submit" class="chemist-add-btn">Send SMS</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        // Custom JavaScript or datetime picker enhancements, if needed
    </script>
@endsection
