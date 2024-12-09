@extends('layouts.owner')

@section('content')
    <div class="row dashboard-detail mt-5">
        <!-- Upgrade Reminder (If Paid is 0) -->
        @if ($user->is_paid === 0 || ($subscription && $subscription->status !== 'active'))
            <div class="alert alert-danger mt-3">
                {{ __('Upgrade package reminder') }} <a href="{{ route('subscription.create') }}">{{ __('Click here') }}</a>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- Dashboard Statistics (Responsive Layout) -->
        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="detail-container1">
                <!-- Font Awesome Icon for SMS Sent -->
                <i class="fas fa-paper-plane ms-3 mt-3 detail-icon"></i>
                <span class="detail-h1">{{ __('messages.Total SmS Sents') }}</span>
                <div class="detail-h2">{{ $smsCount }}</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="detail-container1">
                <!-- Font Awesome Icon for SMS Remaining -->
                <i class="fas fa-clock ms-3 mt-3 detail-icon"></i>
                <span class="detail-h1">{{ __('messages.Total SmS Remaining') }}</span>
                <div class="detail-h2">{{$package->quantity}}/{{ $totalSMSRemaining }}</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="detail-container1">
                <!-- Font Awesome Icon for SMS Remaining -->
                <i class="fas fa-clock ms-3 mt-3 detail-icon"></i>
                <span class="detail-h1">{{ __('messages.Total OTP SmS Remaining') }}</span>
                <div class="detail-h2">1.000/{{ $otpSmsRemaining }}</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="detail-container1">
                <!-- Font Awesome Icon for Customers -->
                <i class="fas fa-users ms-3 mt-3 detail-icon"></i>
                <span class="detail-h1">{{ __('messages.Total Customers') }}</span>
                <div class="detail-h2"> {{$package->customers}} / {{ $customersCount }}</div>
            </div>
        </div>

        <!-- New section for Voucher Redeemed -->
        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="detail-container1">
                <!-- Font Awesome Icon for Voucher Redeemed -->
                <i class="fas fa-gift ms-3 mt-3 detail-icon"></i>
                <span class="detail-h1">{{ __('messages.Voucher Redeemed') }}</span>
                <div class="detail-h2">{{ $redeemCodeCount }}</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="detail-container1">
                <!-- Font Awesome Icon for Voucher Redeemed -->
                <i class="fas fa-qrcode ms-3 mt-3 detail-icon"></i>
                <span class="detail-h1">{{ __('messages.Qr code scan') }}</span>
                <div class="detail-h2">{{ $businessOwner->qr_scan_count }}</div>
            </div>
        </div>


        <div class="col-12 col-sm-12 col-md-6 mb-4">
            <!-- QR Code Section (If Exists) -->
            @if ($businessOwner->qr_code_path)
                <div class="mt-3">
                    <p class="mb-3">{{ __('messages.Scan QR code for qrcode access') }}</p>
                    <img class="img-fluid" src="{{ asset('storage/' . $businessOwner->qr_code_path) }}"
                        alt="{{ __('messages.Chatbot QR Code') }}">
                </div>
            @endif
        </div>
        <div class="col-12 col-sm-12 col-md-6 mb-4">
            <div class="detail-container1">
                <i class="fas fa-chart-line ms-3 mt-3 detail-icon"></i>
                <span class="detail-h1">{{ __('messages.Customer Growth') }}</span>
                <canvas id="customerGrowthChart" class="mt-4"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data for the chart
        const weeks = @json($weeks); // PHP array to JS array
        const totals = @json($totals); // PHP array to JS array

        // Create the chart
        const ctx = document.getElementById('customerGrowthChart').getContext('2d');
        const customerGrowthChart = new Chart(ctx, {
            type: 'line', // Line chart type
            data: {
                labels: weeks.map(week => `Week ${weeks}`), // Display months as labels
                datasets: [{
                    label: 'Customers Added',
                    data: totals, // Total customers per month
                    borderColor: '#4CAF50', // Line color
                    backgroundColor: 'rgba(76, 175, 80, 0.2)', // Background color (semi-transparent)
                    borderWidth: 2,
                    tension: 0.4 // Smooth line curve
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endsection
