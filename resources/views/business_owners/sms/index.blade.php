@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="col-lg-12">
            <h4 class="mt-3">{{ __('messages.Campaign SMS') }}</h4>
            <div class="col-lg-12 justify-content-end d-flex mt-3 flex-column flex-sm-row">
                <a href="{{ route('campaign.sms.create') }}" class="btn orange-button">
                    {{ __('messages.Add SMS') }}
                </a>
            </div>

            <!-- Display Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif



            @if (session('nextpackage') === true)
                <div class="alert alert-danger">
                    <span>You have no SMS remaining for this month. <a href="{{ route('upgrade.sms.package') }}">Upgrade Your
                            Package</a></span>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
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
                        <th>{{ __('messages.Id') }}</th>
                        <th>{{ __('messages.Message') }}</th>
                        <th>{{ __('messages.Planed At') }}</th>
                        <th>{{ __('messages.Sent At') }}</th>
                        <th>{{ __('messages.Status') }}</th>
                        <th>{{ __('messages.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($businessOnwerCampaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($campaign->sms, 50) }}</td>
                            <td>{{ $campaign->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ \Carbon\Carbon::parse($campaign->delivery_date)->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if ($campaign->is_sent == 1)
                                    <span class="badge bg-success">{{ __('messages.Sent') }}</span>
                                @elseif($campaign->is_sent == 0)
                                    <span class="badge bg-warning">{{ __('messages.Pending') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('messages.Failed') }}</span>
                                @endif
                            </td>
                            <td>
                                <!-- Edit Icon -->
                                @if ($campaign->is_sent == 0)
                                    <a href="{{ route('campaign.sms.edit', $campaign->id) }}"
                                        class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ __('messages.Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
/
                                    <!-- Delete Button (via form for POST method) -->
                                    <form action="{{ route('campaign.sms.destroy', $campaign->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE') <!-- This is needed to simulate a DELETE request -->
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('{{ __('messages.Are you sure you want to delete this campaign?') }}')">
                                            <i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                <tfoot>
                    <tr>
                        <th>{{ __('messages.Id') }}</th>
                        <th>{{ __('messages.Message') }}</th>
                        <th>{{ __('messages.Planed At') }}</th>
                        <th>{{ __('messages.Sent At') }}</th>
                        <th>{{ __('messages.Status') }}</th>
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
            new DataTable('#campaign-sms-table', {
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
                },
                order: [
                    [0, 'desc']
                ], // Set ordering to descending based on the first column (change the index if needed)
                responsive: true, // Make the table responsive
            });
        });
    </script>
@endsection
