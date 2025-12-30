@extends('admin.layouts.new_app')

@section('title', 'Yeni Slayt Ekle')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Yeni Slayt Ekle</h3>
        </div>
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.sliders._form')
        </form>
    </div>
@endsection
