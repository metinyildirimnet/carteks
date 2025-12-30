@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Add New Order Status</h1>
        <form action="{{ route('admin.order-statuses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="color">Color</label>
                <input type="color" name="color" id="color" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
