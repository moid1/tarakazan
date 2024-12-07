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
            <h4 class="mt-3">{{ __('messages.Business Owners') }}</h4>
        </div>
        <div class="col-lg-12 justify-content-end d-flex mt-3 flex-column flex-sm-row">
            <a href="{{ route('admin.business.owner.create') }}" class="btn orange-button">
                {{ __('messages.Add Business Owner') }}
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
                        <th>Register Date</th>
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
                            <td>{{ \App\Models\Package::find($businessOwner->package)->name }}</td>
                            <td>{{ $businessOwner->slug }}</td>
                            <td>{{ $businessOwner->created_at->format('d M Y') }}</td>
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
                                    class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Block User Icon -->
                                <a href="{{ route('admin.block.user', ['block_id' => $businessOwner->user->id]) }}"
                                    class="btn btn-sm me-2 {{ $businessOwner->user->is_block ? 'btn-danger' : 'btn-secondary' }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $businessOwner->user->is_block ? 'Unblock' : 'Block' }}">
                                    <i class="fas {{ $businessOwner->user->is_block ? 'fa-unlock' : 'fa-ban' }}"></i>
                                </a>


                                <!-- Delete Icon with Confirmation -->
                                <form class=""
                                    action="{{ route('admin.business.owner.destroy', $businessOwner->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this business owner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mt-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>


                        </tr>
                    @endforeach
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Business Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Package</th>
                        <th>Slug</th>
                        <th>Register Date</th>
                    </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            new DataTable('#example', {
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            let column = this;
                            let title = column.footer().textContent;

                            // Create input element
                            let input = document.createElement('input');
                            input.placeholder = title;
                            column.footer().replaceChildren(input);

                            // Event listener for user input
                            input.addEventListener('keyup', () => {
                                if (column.search() !== this.value) {
                                    column.search(input.value).draw();
                                }
                            });
                        });
                }
            });


        });

        // Initialize Bootstrap tooltips
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
