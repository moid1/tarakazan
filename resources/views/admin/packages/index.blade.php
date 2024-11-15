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
                <h4 class="mt-3">Packages</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('admin.packages.create') }}" class="btn orange-button">
                    Add Package
                </a>

            </div>
        </div>

        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Customers</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
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
                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>


                            <!-- Delete Button -->
                            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
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
