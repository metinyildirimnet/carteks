@extends('admin.layouts.new_app')

@section('title', 'Slaytı Düzenle')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Slaytı Düzenle</h3>
        </div>
        <form action="{{ route('admin.sliders.update', $slide) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.sliders._form')
        </form>
    </div>
@endsection
