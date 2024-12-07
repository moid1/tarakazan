@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="col-lg-6 col-12 d-flex justify-content-start">
            <h4 class="mt-3">{{ __('messages.Subscription') }}</h4> <!-- Translated Header -->
        </div>

        <!-- Table Section -->
        <div class="table-responsive mt-4">
            <table id="example" class="display table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('messages.Id') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Business Name') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Package') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Expiry') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Status') }}</th> <!-- Translated column header -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->id }}</td>
                            <td>{{ $subscription->user->name }}</td>
                            <td>{{ $subscription->package->name }}</td>
                            <td>{{ $subscription->end_date }}</td>
                            <td>{{ $subscription->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('messages.Id') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Business Name') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Package') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Expiry') }}</th> <!-- Translated column header -->
                        <th>{{ __('messages.Status') }}</th> <!-- Translated column header -->
                    </tr>
                </tfoot>
            </table>
        </div>
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
        // Initialize Bootstrap tooltips
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
