@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h4 class="mt-3">{{ __('messages.Customers') }}</h4> <!-- Translated Heading -->
            </div>
            <div class="col-lg-12 justify-content-end d-flex mt-3 flex-column flex-sm-row">
                <a href="{{ route('admin.customer.pdf.export') }}"
                    class="btn btn-info btn-sm text-white mb-2 mb-sm-0 me-sm-2">
                    {{ __('messages.Export to PDF') }} <!-- Translated Button -->
                </a>
                {{-- Uncomment and translate this if you plan to use the Excel export functionality --}}
                {{-- <a href="{{ route('admin.business.owner.exportCsv') }}" class="btn btn-info btn-sm text-white">
                    {{ __('messages.Export to Excel') }} <!-- Translated Button -->
                </a> --}}
            </div>
        </div>

        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('messages.Id') }}</th> <!-- Translated Header -->
                    <th>{{ __('messages.Name') }}</th> <!-- Translated Header -->
                    <th>{{ __('messages.Phone No') }}</th> <!-- Translated Header -->
                    <th>{{ __('messages.Business Owner Name') }}</th> <!-- Translated Header -->
                    <th>Interaction Date</th>
                    <th>Total Redemption</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->businessOwner->business_name }}</td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                        <td>{{ count($customer->redeemCoupon) }}</td>

                        <td>
                            <form class="" action="{{ route('admin.customer.destroy', $customer->id) }}"
                                method="POST" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this Customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            <tfoot>
                <tr>
                    <th>{{ __('messages.Id') }}</th> <!-- Translated Header -->
                    <th>{{ __('messages.Name') }}</th> <!-- Translated Header -->
                    <th>{{ __('messages.Phone No') }}</th> <!-- Translated Header -->
                    <th>{{ __('messages.Business Owner Name') }}</th> <!-- Translated Header -->
                    <th>Interaction Date</th>

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

        // Initialize Bootstrap tooltips
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
