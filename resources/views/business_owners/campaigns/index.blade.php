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
                <h4 class="mt-3">{{ __('messages.Campaigns') }}</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('campaign.create') }}" class="btn orange-button">
                    {{ __('messages.Add New Campaigns') }}
                </a>
            </div>
        </div>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('messages.Id') }}</th>
                    <th>{{ __('messages.Name') }}</th>
                    <th>{{ __('messages.Description') }}</th>
                    <th>{{ __('messages.DateTime') }}</th>
                    <th>{{ __('messages.SMS Sent') }}</th>
                    <th>{{ __('messages.Redeemed') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->id }}</td>
                        <td>{{ $campaign->name }}</td>
                        <td>{{ $campaign->description }}</td>
                        <td>{{ $campaign->created_at->format('d.m.Y') }}</td>
                        <td>{{ $campaign->sms ? $campaign->sms->count() : 0 }}</td>
                        @php
                            $totalRedeemed = 0;
                            if ($campaign->coupons) {
                                foreach ($campaign->coupons as $key => $value) {
                                    // Ensure that RedeemCode is counted correctly, assuming it's a collection or array.
                                    if ($value->RedeemCode) {
                                        $totalRedeemed += count($value->RedeemCode);
                                    }
                                }
                            }
                        @endphp

                        <td>{{ $totalRedeemed }}</td>

                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('campaign.edit', $campaign->id) }}" class="btn btn-warning btn-sm">
                                {{ __('messages.Edit') }}
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('campaign.destroy', $campaign->id) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this campaign?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('messages.Delete') }}</button>
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
        new DataTable('#example', {
                order: [
                    [0, 'desc']
                ], // Set ordering to descending based on the first column (change the index if needed)
                responsive: true, // Make the table responsive
            }

        );
    </script>
@endsection
