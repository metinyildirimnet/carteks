@extends('admin.layouts.new_app')

@section('title', 'Yeni Ana Sayfa Bloğu Ekle')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Yeni Ana Sayfa Bloğu Ekle</h3>
            </div>
            <form action="{{ route('admin.homepage-blocks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.homepage_blocks._form')
            </form>
        </div>
    </div>
</div>
@endsection
