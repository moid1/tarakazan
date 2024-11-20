@extends('layouts.owner')

@section('content')
    <div class="row dashboard-detail mt-5">
        @if ($user->is_paid === 0)
            <div class="alert alert-danger mt-3">
                You have to upgrade the package please! <a href="{{ route('subscription.create') }}">Click here</a>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="{{ 'images/dash detail 1.svg' }}" alt="" />
                <span class="detail-h1">Total SmS Sents</span>
                <div class="detail-h2">{{ $smsCount }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="{{ asset('images/dash detail 2.svg') }}" alt="" />
                <span class="detail-h1">Total SmS Remaining</span>
                <div class="detail-h2">{{ $totalSMSRemaining }}</div>


            </div>
        </div>
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="{{ asset('images/dash detail 3.svg') }}" alt="" />
                <span class="detail-h1">Total Customers</span>
                <div class="detail-h2">{{ $customersCount }}</div>
            </div>
        </div>

        @if ($businessOwner->qr_code_path)
            <div class="mt-3">
                <p class="mb-3"> Scan the QR code to access your chatbot</p>
                <img class="img-fluid" src="{{ asset('storage/' . $businessOwner->qr_code_path) }}" alt="Chatbot QR Code">
            </div>
        @endif

    </div>
@endsection
