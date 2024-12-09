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
                <h4 class="mt-3">{{ __('messages.Waiters') }}</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('waiter.create') }}" class="btn orange-button">
                    {{ __('messages.Add New Waiter') }}
                </a>
            </div>
        </div>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('messages.Id') }}</th>
                    <th>{{ __('messages.Name') }}</th>
                    <th>{{ __('messages.Email') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($waiters as $waiter)
                    <tr>
                        <td>{{ $waiter->id }}</td>
                        <td>{{ $waiter->user->name }}</td>
                        <td>{{ $waiter->user->email }}</td>
                        <td>


                            <!-- Delete Button -->
                            <form action="{{ route('waiter.destroy', $waiter->id) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this waiter?') }}');">
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
        new DataTable('#example',{
            order: [[0, 'desc']], // Set ordering to descending based on the first column (change the index if needed)
            responsive: true,      // Make the table responsive
        });
    </script>
@endsection
