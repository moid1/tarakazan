@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h4 class="mt-3">{{ __('messages.Packages') }}</h4> <!-- Translated header -->
            </div>
            <div class="col-lg-12 justify-content-end d-flex flex-column flex-sm-row">
                <a href="{{ route('admin.packages.create') }}" class="btn orange-button">
                    {{ __('messages.Add Package') }} <!-- Translated button text -->
                </a>
            </div>
        </div>

        <!-- Responsive Table Wrapper -->
        <div class="table-responsive mt-3">
            <table id="example" class="display table" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('messages.Id') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Name') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Customers') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Price') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Quantity') }}</th> <!-- Translated column header -->
                        <th>Total In Used</th>
                        <th>{{ __('messages.Action') }}</th> <!-- Translated column header -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packages as $package)
                        <tr>
                            <td>{{ $package->id }}</td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->customers }}</td>
                            <td>{{ $package->price }}</td>
                            <td>{{ $package->quantity }}</td>
                            <th>{{$package->total_used}}</th>
                            <td>
                                <!-- Edit Icon -->
                                <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-warning btn-sm"
                                    data-bs-toggle="tooltip" title="{{ __('messages.Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete Icon -->
                                <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this package?') }}');">
                                    <!-- Translated confirmation text -->
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
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
