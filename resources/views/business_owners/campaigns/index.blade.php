@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
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
                <tr>
                    <td>{{ $campaign->id }}</td>
                    <td>{{ $campaign->name }}</td>
                    <td>{{ $campaign->description }}</td>
                    <td>
                            <!-- Edit Button -->
                            <a href="{{ route('campaign.edit', $campaign->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('campaign.destroy', $campaign->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this campaign?');">
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
