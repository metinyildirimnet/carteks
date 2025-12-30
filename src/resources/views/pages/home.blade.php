@extends('layouts.app')

@section('title', 'Anasayfa')

@push('styles')
<style>
    .hero-slider .slide {
        position: relative; /* Not absolute, to allow container to grow */
        display: none;
    }
    .hero-slider .slide.active {
        display: block;
    }
    .slide-background {
        display: block;
        width: 100%;
        height: auto; /* This makes the image responsive */
    }
    .slide-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
        width: 90%;
        text-align: center;
    }
    .hide-on-mobile { display: none !important; }
    .visual-block-image { width: 100%; height: auto; border-radius: 8px; }

    /* On mobile screens, override the fixed 600px height from style.css */
    @media (max-width: 768px) {
        .hero-slider {
            height: auto !important;
        }
    }
    @media (min-width: 769px) {
        .hide-on-desktop { display: none !important; }
        .hide-on-mobile { display: block !important; }
    }
</style>
@endpush

@section('content')
    @if(isset($slides) && $slides->isNotEmpty())
    <section class="hero-slider">
        @foreach($slides as $slide)
        <div class="slide {{ $loop->first ? 'active' : '' }}">
            <picture>
                <source media="(max-width: 768px)" srcset="{{ $slide->mobile_image_url }}">
                <img src="{{ $slide->desktop_image_url }}" alt="Slide" class="slide-background">
            </picture>
            <div class="slide-content">
                @if($slide->content)
                    {!! $slide->content !!}
                @endif
                @if($slide->button_text && ($slide->button_url || $slide->linkable_id))
                    <a href="{{ $slide->getButtonUrl() }}" class="btn">{{ $slide->button_text }}</a>
                @endif
            </div>
        </div>
        @endforeach
        
        @if($slides->count() > 1)
        <div class="slider-nav">
            @foreach($slides as $index => $slide)
                <button class="dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $index }}"></button>
            @endforeach
        </div>
        @endif
    </section>
    @endif

    <div class="container">
        @forelse ($homepageBlocks as $block)
            @if($block->is_active)
                @php
                    $visibility_classes = '';
                    if (!$block->show_on_desktop) $visibility_classes .= ' hide-on-desktop';
                    if (!$block->show_on_mobile) $visibility_classes .= ' hide-on-mobile';
                @endphp

                <div class="block-section {{ $visibility_classes }}" style="margin-bottom: 20px; width: 100%;">
                    @if($block->type === 'product')
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0px;">
                            <div>
                                <h2 class="section-title" style="text-align: left; margin-bottom: 5px;">{{ $block->title }}</h2>
                                @if ($block->description)
                                    <p class="section-subtitle" style="text-align: left; margin-top: 0;">{{ $block->description }}</p>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('block.show', $block->slug) }}" class="btn-secondary"
                                    style="margin-bottom:15px; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Tüm Ürünler</a>
                            </div>
                        </div>
                        <div class="products-grid">
                            @forelse ($block->products as $product)
                                @include('partials._product_card', ['product' => $product])
                            @empty
                                <p style="text-align: center; width: 100%;">Bu blokta henüz ürün bulunmuyor.</p>
                            @endforelse
                        </div>
                    @elseif($block->type === 'visual' && $block->image_path)
                        @if($block->getLink())
                            <a href="{{ $block->getLink() }}">
                                <img src="{{ $block->image_url }}" alt="{{ $block->title }}" class="visual-block-image">
                            </a>
                        @else
                            <img src="{{ $block->image_url }}" alt="{{ $block->title }}" class="visual-block-image">
                        @endif
                    @endif
                </div>
            @endif
        @empty
            <p style="text-align: center; width: 100%;">Henüz ana sayfa bloğu bulunmuyor.</p>
        @endforelse
    </div>

    <section class="info-bar">
        <div class="container">
            <div class="info-item">
                <i class="fas fa-check-circle"></i>
                <h4>ORİJİNAL ÜRÜN</h4>
                <p>Orijinal Ürün Garantisi!</p>
            </div>
            <div class="info-item">
                <i class="fas fa-shield-alt"></i>
                <h4>GÜVENLI ALIŞVERIŞ</h4>
                <p>%100 güvenli ödeme sistemi</p>
            </div>
            <div class="info-item">
                <i class="fas fa-shipping-fast"></i>
                <h4>HIZLI TESLIMAT</h4>
                <p>Aynı gün kargoya teslim</p>
            </div>
        </div>
    </section>
@endsection
