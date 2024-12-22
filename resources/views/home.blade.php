@extends('layouts.admin')

@section('content')
    <div class="row dashboard-detail">
        <!-- Total Active and Inactive Businesses -->
        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <span class="detail-h1">
                    <i class="fas fa-check-circle text-success me-2"></i>{{ __('messages.Total Active Businesses') }} /
                    <i class="fas fa-times-circle text-danger me-2"></i>{{ __('messages.Total InActive Businesses') }}
                </span>
                <div class="detail-h2">{{ $totalActiveBusiness }} / {{ $totalInActiveBusiness }}</div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <span class="detail-h1">
                    <i class="fas fa-users me-2"></i>{{ __('messages.Total Customers') }}
                </span>
                <div class="detail-h2">{{ $customerCount }}</div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <span class="detail-h1">
                    <i class="fas fa-dollar-sign me-2"></i>{{ __('messages.Total Revenue') }}
                </span>
                <div class="detail-h2">{{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>

        <!-- Total QR Scans -->
        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <span class="detail-h1">
                    <i class="fas fa-qrcode me-2"></i>{{ __('messages.Total QR Scans') }}
                </span>
                <div class="detail-h2">{{ $totalQRScanCount }}</div>
            </div>
        </div>

        <!-- Total Reviews -->
        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <span class="detail-h1">
                    <i class="fas fa-star me-2"></i>{{ __('messages.Total Reviews') }}
                </span>
                <div class="detail-h2">{{ $totalReviewsCount }}</div>
            </div>
        </div>

        <!-- Monthly Revenue (Current vs Previous Month) -->
        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <span class="detail-h1">
                    <i class="fas fa-calendar-day me-2"></i>{{ __('Monthly Revenue') }}
                </span>
                <div class="">
                    {{ __('messages.Current Month') }}:
                    ${{ number_format($monthlyRevenueData['currentMonthRevenue'], 2) }} <br>
                    {{ __('messages.Previous Month') }}:
                    ${{ number_format($monthlyRevenueData['previousMonthRevenue'], 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-6">
            <canvas id="businessOwnersChart"></canvas>
        </div>
        <div class="col-lg-1"></div>
        <!-- Package Usage Chart -->
        <div class="col-lg-5">
            <canvas id="packageUsagePieChart"></canvas>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6">
            <h5 class="mt-5">{{__('messages.Top Performing Business Owners')}}</h5>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{__('messages.Business Name')}}</th>
                        <th>{{__('messages.QR Scans')}}</th>
                        <th>{{__('messages.Google Reviews')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topPerformers as $business)
                        <tr>
                            <td>{{ $business->business_name }}</td>
                            <td>{{ $business->qr_scan_count }}</td>
                            <td>{{ $business->google_review_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-lg-6">
            <h5 class="mt-5">Most Frequent Hours When Customer Registered / Scan the QR Code</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            {{ __('messages.Hour') }}
                        <th>{{__('messages.Number of Registrations / Scan QRs')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($getMostFrequentRegistrationHour as $time)
                        <tr>
                            <td>{{ $time->registration_hour }}</td>
                            <td>{{ $time->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('businessOwnersChart').getContext('2d');
        var businessOwnersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: {!! json_encode(__('messages.Business Owners Added')) !!},
                    data: @json($counts),
                    backgroundColor: '#ff9600',
                    borderColor: '#ff9600',
                    borderWidth: 1,
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Business Owners'
                        }
                    },
                }
            }
        });

        // Package Usage Pie Chart
        var ctx2 = document.getElementById('packageUsagePieChart').getContext('2d');
        var packageUsagePieChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: @json($packageNames),
                datasets: [{
                    label: 'Business Owners Per Package',
                    data: @json($packageCounts),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        '#36A2EB',
                        '#FF6384',
                        '#FF9F40',
                        '#4BC0C0',
                        '#9966FF',
                        '#FFCD56'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endsection
