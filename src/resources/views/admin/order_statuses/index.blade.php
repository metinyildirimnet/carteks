@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Order Statuses</h1>
        <a href="{{ route('admin.order-statuses.create') }}" class="btn btn-primary">Add New Status</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderStatuses as $status)
                    <tr>
                        <td>{{ $status->name }}</td>
                        <td><span style="background-color: {{ $status->color }}; padding: 5px 10px; border-radius: 5px; color: #fff;">{{ $status->color }}</span></td>
                        <td>
                            <a href="{{ route('admin.order-statuses.edit', $status) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.order-statuses.destroy', $status) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
