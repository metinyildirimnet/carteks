@extends('admin.layouts.new_app')

@section('title', 'Yeni Sayfa Oluştur')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Yeni Sayfa Oluştur</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @include('admin.pages._form')
        </form>
    </div>
@endsection
