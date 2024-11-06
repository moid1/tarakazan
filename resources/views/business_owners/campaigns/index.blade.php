@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h4 class="mt-3">Campaigns</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('campaign.create') }}" class="btn orange-button">
                    Add New Campaigns
                </a>

            </div>
        </div>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($campaigns as $campaign)
                    <td>{{ $campaign->id }}</td>
                    <td>{{ $campaign->name }}</td>
                    <td>{{ $campaign->description }}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <!-- Delete Button -->
                        <form action="" method="POST" style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this business owner?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
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