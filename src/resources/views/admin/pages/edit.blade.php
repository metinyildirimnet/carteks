@extends('admin.layouts.new_app')

@section('title', 'Sayfayı Düzenle')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">"{{ $page->title }}" Sayfasını Düzenle</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('admin.design.pages.update', $page) }}" method="POST">
            @method('PUT')
            @include('admin.pages._form')
        </form>
    </div>
@endsection
