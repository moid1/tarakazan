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
            <h4 class="mt-3">Business Owners</h4>
        </div>
        <div class="col-lg-12 justify-content-end d-flex mt-3 flex-column flex-sm-row">
            <a href="{{ route('admin.business.owner.create') }}" class="btn orange-button">
                Add Business Owner
            </a>
        </div>

        <!-- Export Buttons -->
        <div class="col-lg-12 justify-content-end d-flex mt-3 flex-column flex-sm-row">
            <a href="{{ route('admin.business.owner.exportPdf') }}"
                class="btn btn-info btn-sm text-white mb-2 mb-sm-0 me-sm-2">
                Export to PDF
            </a>
            <a href="{{ route('admin.business.owner.exportCsv') }}" class="btn btn-info btn-sm text-white">
                Export to Excel
            </a>
        </div>

        <!-- Table Section -->
        <div class="table-responsive mt-4">
            <table id="example" class="display table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Business Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Package</th>
                        <th>Slug</th>
                        <th>QR Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($businessOwners as $businessOwner)
                        <tr>
                            <td>{{ $businessOwner->id }}</td>
                            <td>{{ $businessOwner->business_name }}</td>
                            <td>{{ $businessOwner->address }}</td>
                            <td>{{ $businessOwner->business_email }}</td>
                            <td>{{ $businessOwner->package }}</td>
                            <td>{{ $businessOwner->slug }}</td>
                            <td>
                                @if ($businessOwner->qr_code_path)
                                    <!-- QR Code Icon with Download Link -->
                                    <a href="{{ asset('storage/' . $businessOwner->qr_code_path) }}"
                                        download="{{ $businessOwner->slug }}-qr-code.png" class="btn btn-success btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Download QR Code">
                                        <i class="fas fa-qrcode"></i> <!-- QR Code icon -->
                                    </a>
                                @else
                                    <span>No QR Code</span>
                                @endif
                            </td>
                            <td>
                                <!-- Edit Icon -->
                                <a href="{{ route('admin.business.owner.edit', $businessOwner->id) }}"
                                    class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete Icon with Tooltip -->
                                <form action="{{ route('admin.business.owner.destroy', $businessOwner->id) }}"
                                    method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this business owner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>

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
