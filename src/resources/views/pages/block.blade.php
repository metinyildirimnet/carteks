@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="my-4">{{ $block->title }}</h1>
            @if($block->description)
                <p class="lead">{{ $block->description }}</p>
            @endif
        </div>
    </div>

    <div class="products-grid" style="margin-top: 20px;">
        @forelse($block->products as $product)
            @include('partials._product_card', ['product' => $product])
        @empty
            <div class="col-12">
                <p>Bu blokta henüz ürün bulunmamaktadır.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
