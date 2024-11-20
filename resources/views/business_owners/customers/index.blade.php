@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h4 class="mt-3">Customers</h4>
            </div>
            <div class="col-lg-12 justify-content-end d-flex mt-3 flex-column flex-sm-row">
                <a href="{{ route('admin.customer.pdf.export') }}"
                    class="btn btn-info btn-sm text-white mb-2 mb-sm-0 me-sm-2">
                    Export to PDF
                </a>
                {{-- <a href="{{ route('admin.business.owner.exportCsv') }}" class="btn btn-info btn-sm text-white">
                    Export to Excel
                </a> --}}
            </div>


        </div>

        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Phone No</th>
                    <th>Business Owner Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->businessOwner->business_name }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
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
