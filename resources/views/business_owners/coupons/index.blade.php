@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h4 class="mt-3">Coupons</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('coupon.create') }}" class="btn orange-button">
                    Add New Coupon
                </a>

            </div>
        </div>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Expiry Date</th>
                    <th>Gift</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <td>{{ $owner->id }}</td>
                    <td>{{ $coupon->name }}</td>
                    <td>{{ $coupon->expiry_date }}</td>
                    <td>{{ $coupon->gift }}</td>
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
