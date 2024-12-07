@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h4 class="mt-3">{{ __('messages.Coupons') }}</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('coupon.create') }}" class="btn orange-button">
                    {{ __('messages.Add New Coupon') }}
                </a>
            </div>
        </div>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('messages.Id') }}</th>
                    <th>{{ __('messages.Name') }}</th>
                    <th>{{ __('messages.Creation Date') }}</th>
                    <th>{{ __('messages.Expiry Date') }}</th>
                    <th>{{ __('messages.Gift') }}</th>
                    <th>{{ __('messages.Creation Date') }}</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->created_at->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d M Y') }}</td>
                        <td>{{ $coupon->gift }}</td>
                        <td>{{$coupon->created_at->format('d M Y | h:m')}}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th>{{ __('messages.Id') }}</th>
                        <th>{{ __('messages.Name') }}</th>
                        <th>{{ __('messages.Creation Date') }}</th>
                        <th>{{ __('messages.Expiry Date') }}</th>
                        <th>{{ __('messages.Gift') }}</th>
                        <th>{{ __('messages.Creation Date') }}</th>
    
                    </tr>
                </tfoot>
            </tbody>
        </table>
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
    </script>
@endsection
