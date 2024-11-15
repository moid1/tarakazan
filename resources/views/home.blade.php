@extends('layouts.admin')

@section('content')
    <div class="row dashboard-detail ">
        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <img class="ms-3 mt-3" src="{{ asset('images/dash detail 3.svg') }}" alt="Total Active Businesses" />
                <span class="detail-h1">Total Active Businesses</span>
                <div class="detail-h2">{{ $totalActiveBusiness }}</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <img class="ms-3 mt-3" src="{{ asset('images/dash detail 2.svg') }}" alt="Total Customers" />
                <span class="detail-h1">Total Customers</span>
                <div class="detail-h2">0</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="detail-container1">
                <img class="ms-3 mt-3" src="{{ asset('images/dash detail 1.svg') }}" alt="Total Revenue" />
                <span class="detail-h1">Total Revenue</span>
                <div class="detail-h2">0</div>
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
@endsection


@section('customjs')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('businessOwnersChart').getContext('2d');

        var businessOwnersChart = new Chart(ctx, {
            type: 'bar', // Ensure this is set to 'bar' for bar chart
            data: {
                labels: @json($months), // Months from controller
                datasets: [{
                    label: 'Business Owners Added',
                    data: @json($counts), // Counts of business owners from controller
                    backgroundColor: '#ff9600', // Bar color
                    borderColor: '#ff9600', // Border color of bars
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
            type: 'pie', // Using pie chart for package usage
            data: {
                labels: @json($packageNames), // Package names
                datasets: [{
                    label: 'Business Owners Per Package',
                    data: @json($packageCounts), // Count of business owners for each package
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)', // Blue
                        'rgba(255, 99, 132, 0.5)', // Red
                        'rgba(255, 159, 64, 0.5)', // Orange
                        'rgba(75, 192, 192, 0.5)', // Green
                        'rgba(153, 102, 255, 0.5)', // Purple
                        'rgba(255, 159, 64, 0.5)' // Yellow
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
