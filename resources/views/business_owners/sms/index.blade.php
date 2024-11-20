@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="col-lg-12">
            <h4 class="mt-3"> Campaign SMS</h4>
            <div class="col-lg-12 justify-content-end d-flex mt-3 flex-column flex-sm-row">
                <a href="{{ route('campaign.sms.create') }}" class="btn orange-button">
                    Add SMS
                </a>
            </div>

            <!-- Display Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display Error Message -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- List of Campaign SMS -->
            <table id="campaign-sms-table" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Message</th>
                        <th>Delivery Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($businessOnwerCampaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->id }}</td>
                            <td>{{ $campaign->sms }}</td>
                            <td>{{ \Carbon\Carbon::parse($campaign->delivery_date)->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if ($campaign->is_sent == 1)
                                    <span class="badge bg-success">Sent</span>
                                @elseif($campaign->is_sent == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                <!-- Edit Icon -->
                                @if ($campaign->is_sent == 0)
                                    <a href="{{ route('campaign.sms.edit', $campaign->id) }}" class="btn btn-warning btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>


                                    <!-- Delete Button (via form for POST method) -->
                                    <form action="{{ route('campaign.sms.destroy', $campaign->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE') <!-- This is needed to simulate a DELETE request -->
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this campaign?')">Delete</button>
                                    </form>
                                @endif
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
            // Initialize DataTable for campaign list
            new DataTable('#campaign-sms-table', {
                responsive: true, // Enable responsive plugin
                paging: true, // Enable pagination
                lengthChange: false, // Hide the page length menu
                searching: true, // Enable search box
                info: true, // Show information about number of entries
                autoWidth: false, // Prevent automatic column width calculation
            });
        });
    </script>
@endsection
