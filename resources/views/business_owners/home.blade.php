@extends('layouts.owner')

@section('content')
    <div class="row dashboard-detail mt-5">
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="{{ 'images/dash detail 1.svg' }}" alt="" />
                <span class="detail-h1">Total SmS Sents</span>
                <div class="detail-h2">0</div>
            </div>
        </div>
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="{{ asset('images/dash detail 2.svg') }}" alt="" />
                <span class="detail-h1">Total SmS Remaining</span>
                <div class="detail-h2">0</div>


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
