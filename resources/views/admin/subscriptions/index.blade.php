@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="col-lg-6 col-12 d-flex justify-content-start">
            <h4 class="mt-3">Subscription</h4>
        </div>
       

       

        <!-- Table Section -->
        <div class="table-responsive mt-4">
            <table id="example" class="display table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Business Name</th>
                        <th>Package</th>
                        <th>Expiry</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->id }}</td>
                            <td>{{ $subscription->user->name }}</td>
                            <td>{{ $subscription->package->name }}</td>
                            <td>{{$subscription->end_date}}</td>
                            <th>{{$subscription->status}}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            // Initialize DataTable with the responsive option enabled
            new DataTable('#example', {
                responsive: true, // Enable responsive plugin
                paging: true, // Enable pagination
                lengthChange: false, // Hide the page length menu
                searching: true, // Enable search box
                info: true, // Show information about number of entries
                autoWidth: false, // Prevent automatic column width calculation
                columnDefs: [{
                    targets: -1, // Action column (Edit, Delete)
                    orderable: false, // Disable sorting for the action column
                }],
            });
        });

        // Initialize Bootstrap tooltips
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
