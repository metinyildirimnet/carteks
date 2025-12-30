@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Edit Order Status</h1>
        <form action="{{ route('admin.order-statuses.update', $orderStatus) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $orderStatus->name }}" required>
            </div>
            <div class="form-group">
                <label for="color">Color</label>
                <input type="color" name="color" id="color" class="form-control" value="{{ $orderStatus->color }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
