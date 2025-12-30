@extends('layouts.app')

@section('title', $product->title)

@section('content')
    <div class="container" style="margin-bottom: 50px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                @if ($product->category)
                    <li class="breadcrumb-item"><a href="#">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
            </ol>
        </nav>
        <div class="product-detail-container" style="display: flex; flex-wrap: wrap; gap: 30px;">
            <div class="product-images"
                style="flex: 1; min-width: 300px; max-width: 500px; height: 500px; overflow: hidden; position: relative;">
                <!-- Swiper -->
                <div class="swiper-container gallery-top">
                    <div class="swiper-wrapper">
                        @if ($product->images->isNotEmpty())
                            @foreach ($product->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ $image->image_path }}" alt="{{ $product->title }}" width="100%"
                                        height="100%">
                                </div>
                            @endforeach
                        @else
                            <div class="swiper-slide">
                                <img src="https://via.placeholder.com/400x300.png?text=No+Image" alt="No Image">
                            </div>
                        @endif
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div class="swiper-container gallery-thumbs">
                    <div class="swiper-wrapper">
                        @if ($product->images->isNotEmpty())
                            @foreach ($product->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ $image->image_path }}" alt="{{ $product->title }}" width="100%"
                                        height="100%">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="product-info" style="flex: 1; min-width: 300px;">
                <h1 style="font-size: 28px; margin-top: 0;">{{ $product->title }}</h1>
                <div class="price" style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    @if ($product->discounted_price && $product->discounted_price < $product->price)
                        @php
                            $discountPercentage = round(
                                (($product->price - $product->discounted_price) / $product->price) * 100,
                            );
                        @endphp
                        <span class="badge badge-success"
                            style="height: 100%; min-height: 37px; display: flex; align-items:center;">-%{{ $discountPercentage }}</span>
                        <div style="display: flex; flex-direction: column; align-items: flex-start;">
                            <span style="text-decoration: line-through; color: #999; font-size:14px; font-weight: normal;">
                                {{ number_format($product->price, 2) }} ₺
                            </span>
                            <span style="font-size: 18px; font-weight: normal; color: #333;">
                                {{ number_format($product->discounted_price, 2) }} ₺
                            </span>
                        </div>
                    @else
                        <div style="font-size: 18px; font-weight: normal; color: #333;">
                            {{ number_format($product->price, 2) }} ₺
                        </div>
                    @endif
                </div>
                <p style="line-height: 1.6;">{{ $product->description }}</p>

                {{-- Package Contents Section --}}
                @if ($product->is_package && $product->containedProducts->isNotEmpty())
                    <div class="package-contents">
                        <span class="package-title">Paket İçindeki Ürünler</span>
                        <ul class="list-group list-group-flush">
                            @foreach ($product->containedProducts as $containedProduct)
                                <li class="list-group-item" style="display: flex; align-items: center; padding-left: 0;">
                                    @if ($containedProduct->images->first())
                                        <img src="{{ asset($containedProduct->images->first()->image_path) }}"
                                            alt="{{ $containedProduct->title }}"
                                            style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px; border-radius: 4px;">
                                    @else
                                        <img src="https://via.placeholder.com/50x50.png?text=No+Image" alt="No Image"
                                            style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px; border-radius: 4px;">
                                    @endif
                                    <div style="font-weight: 500;">
                                        <a href="{{ $containedProduct->url }}"
                                            style="text-decoration: none; color: inherit;">{{ $containedProduct->title }}</a>
                                        <span style="color: #6c757d; font-weight: normal;"> -
                                            {{ $containedProduct->pivot->quantity }} Adet</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="product-extras">
                    <div class="extras-box extras-review">
                        <i class="fa fa-solid fa-fire mr-2"></i>
                        <div>
                            <span id="viewing-count"></span> kişi şu an bu ürünü inceliyor!
                        </div>
                    </div>
                    <div class="extras-box extras-stock">
                        <div class="icon-dot"></div>
                        <span><strong>Sınırlı Stok!</strong> - Anlık <span id="in-cart-count"></span> Kişinin
                            Sepetinde</span>
                    </div>
                </div>

                <div class="buttons" style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
                    <button type="button" class="btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}"
                        style="width: 100%; height: 50px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 16px; background-color: #28a745; border-color: #28a745;">
                        <i class="fas fa-shopping-cart"></i> Sepete Ekle
                    </button>
                    <a href="{{ route('cart.buyNow', $product) }}" class="btn-secondary"
                        style="width: 100%; height: 50px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 16px; background-color: #dc3545; border-color: #dc3545; color: white;">
                        <i class="fas fa-bolt"></i> Hemen Al
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Swiper Gallery
            var galleryThumbs = new Swiper('.gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                loop: false,
                freeMode: true,
                loopedSlides: 5, //looped slides should be the same
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
            var galleryTop = new Swiper('.gallery-top', {
                spaceBetween: 10,
                loop: true,
                loopedSlides: 5, //looped slides should be the same
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: galleryThumbs,
                },
            });

            // Dynamic "in cart" and "viewing" notification
            const viewingCountSpan = document.getElementById('viewing-count');
            const inCartCountSpan = document.getElementById('in-cart-count');

            const minViewing = 20;
            const maxViewing = 50;
            const minInCart = 5;
            const maxInCart = 25;

            const randomViewingCount = Math.floor(Math.random() * (maxViewing - minViewing + 1)) + minViewing;
            const randomInCartCount = Math.floor(Math.random() * (maxInCart - minInCart + 1)) + minInCart;

            viewingCountSpan.textContent = randomViewingCount;
            inCartCountSpan.textContent = randomInCartCount;
        });
    </script>

    @if (isset($settings['facebook_pixel_id']) && $settings['facebook_pixel_id']->value)
        <script>
            fbq('track', 'ViewContent', {
                content_name: '{{ $product->title }}',
                content_category: '{{ $product->category->name ?? 'N/A' }}',
                content_ids: ['{{ $product->id }}'],
                content_type: 'product',
                value: {{ $product->discounted_price ?? $product->price }},
                currency: 'TRY'
            });
        </script>
    @endif
@endsection
