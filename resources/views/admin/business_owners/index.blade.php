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
                <h4 class="mt-3">Business Owners</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('admin.business.owner.create') }}" class="btn orange-button">
                    Add Business Owner
                </a>
            </div>
           
        </div>

        <div class="col-lg-12 justify-content-end d-flex">
            <a href="{{ route('admin.business.owner.exportPdf') }}" class="btn btn-info btn-sm text-white">
                Export to PDF
            </a>&nbsp;
            <a href="{{ route('admin.business.owner.exportCsv') }}" class="btn btn-info btn-sm text-white">
                Export to Excel
            </a>
        </div>

        <table id="example" class="display" style="width:100%">
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
                                <a href="{{ asset('storage/' . $businessOwner->qr_code_path) }}"
                                    download="{{ $businessOwner->slug }}-qr-code.png" class="btn btn-success btn-sm mt-2">QR
                                    Code</a>
                            @else
                                <span>No QR Code</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.business.owner.edit', $businessOwner->id) }}"
                                class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('admin.business.owner.destroy', $businessOwner->id) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this business owner?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection

@section('customjs')
    <script>
        new DataTable('#example');
    </script>
@endsection
