@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="col-lg-12">
            <h4 class="mt-3">{{ __('messages.Send Campaign SMS') }}</h4>

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
                    <label for="campaign_id">{{ __('messages.Campaign') }}</label>
                    <select name="campaign_id" id="campaign_id" class="form-control" required>
                        <option value="" disabled selected>{{ __('messages.Select Campaign') }}</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" id="sms_limit" name="sms_limit" id="" value="1">
                <!-- Message Field -->
                <div class="form-group mt-3">
                    <label for="message">{{ __('messages.Message') }}</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4"
                        placeholder="{{ __('messages.Enter your campaign message') }}" required>{{ old('message') }}</textarea>

                    @error('message')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- SMS Count -->
                <div class="form-group mt-3">
                    <small id="sms-count" class="text-muted">{{ __('messages.SMS Count: 1') }}</small>
                </div>

                <!-- Button to Insert Line Break -->
                <div class="form-group mt-3">
                    <button type="button" class="btn btn-secondary" id="insert-line-break-btn">
                        {{ __('messages.Insert Line Break') }}
                    </button>
                </div>

                <!-- Delivery Time -->
                <div class="form-group mt-3">
                    <label for="delivery_time">{{ __('messages.Delivery Time') }}</label>
                    <input type="datetime-local" class="form-control @error('delivery_time') is-invalid @enderror"
                        id="delivery_time" name="delivery_time" required value="{{ old('delivery_time') }}">

                    @error('delivery_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mt-5">
                    <button type="submit" class="chemist-add-btn">{{ __('messages.Send SMS') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        // Function to update SMS count based on message length
        function updateSmsCount() {
            const message = document.getElementById('message').value;
            const smsCountElement = document.getElementById('sms-count');
            const smsServer = document.getElementById('sms_limit');
            const maxLengthPerSms = 160; // Max length for a single SMS
            const smsOverhead = 7; // SMS encoding overhead for multi-part messages
            const totalLength = message.length + 70;

            if (totalLength === 0) {
                smsCountElement.textContent = 'SMS Count: 1';
                return;
            }

            // Calculate how many SMS are required
            let smsCount = Math.ceil(totalLength / (maxLengthPerSms - smsOverhead));
            smsCountElement.textContent = `SMS Count: ${smsCount}`;
            smsServer.value = smsCount;
        }

        // Function to insert a line break at the cursor's position
        function insertLineBreak() {
            const messageField = document.getElementById('message');
            const cursorPos = messageField.selectionStart; // Get the current cursor position
            const message = messageField.value; // Get the current content of the textarea

            // Insert the literal '\\n' at the current cursor position
            const newMessage = message.substring(0, cursorPos) + '\\n' + message.substring(cursorPos);

            // Update the textarea's value with the new message (including the '\\n')
            messageField.value = newMessage;

            // Move the cursor to right after the inserted '\\n'
            const newCursorPos = cursorPos + '\\n'.length;

            // Set the cursor to the new position
            messageField.selectionStart = messageField.selectionEnd = newCursorPos;

            // Focus back on the message input field
            messageField.focus();
        }



        // Event listener to update SMS count in real-time
        document.getElementById('message').addEventListener('input', updateSmsCount);

        // Event listener for the "Insert Line Break" button
        document.getElementById('insert-line-break-btn').addEventListener('click', insertLineBreak);

        // Initial call to set the correct SMS count on page load
        updateSmsCount();
    </script>
@endsection
