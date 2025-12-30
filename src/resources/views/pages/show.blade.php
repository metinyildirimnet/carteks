@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div class="">
                    <h1 class="card-title">{{ $page->title }}</h1>
                    <hr>
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-content img {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush
