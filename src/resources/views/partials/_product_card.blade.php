<div class="product-card">
    <a href="{{ $product->url }}">
        <div class="product-image-container">
            @if ($product->images->first())
                <img src="{{ $product->images->first()->image_path }}" alt="{{ $product->title }}">
            @else
                <img src="https://via.placeholder.com/280x200.png?text=No+Image" alt="No Image">
            @endif
        </div>
    </a>
    <a href="{{ $product->url }}">
        <h3 class="product-title">{{ $product->title }}</h3>
    </a>
    <p class="product-description">{{ $product->description }}</p>
    <div class="product-rating" style="margin-bottom: 10px; display: flex; align-items: center;">
        @php
            $averageRating = $product->average_rating;
            $totalReviews = $product->total_reviews;
        @endphp

        @if ($totalReviews > 0)
            <div class="stars" style="color: #ffc107; margin-right: 5px;">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $averageRating)
                        <i class="fas fa-star"></i> <!-- Filled star -->
                    @elseif ($i - 0.5 <= $averageRating)
                        <i class="fas fa-star-half-alt"></i> <!-- Half star -->
                    @else
                        <i class="far fa-star"></i> <!-- Empty star -->
                    @endif
                @endfor
            </div>
            <span style="font-size: 12px; color: #666;">({{ $totalReviews }} değerlendirme)</span>
        @else
            <span style="font-size: 12px; color: #666;">Henüz değerlendirme yok</span>
        @endif
    </div>
    <div class="price" style="display: flex; align-items: center; gap: 8px;">
        @if ($product->discounted_price && $product->discounted_price < $product->price)
            @php
                $discountPercentage = round((($product->price - $product->discounted_price) / $product->price) * 100);
            @endphp
            <span class="badge badge-success"
                style="height: 100%; display: flex; align-items: center;">-%{{ $discountPercentage }}</span>
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
    <div class="buttons">
        <div class="button-group-top">
            <button type="button" class="btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}">
                <!--svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                    </path>
                </!--svg-->
                Sepete Ekle
            </button>
            <a href="{{ route('cart.buyNow', $product) }}" class="btn-secondary">
                <!--svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </!--svg-->
                Hemen Al
            </a>
        </div>
        <div class="button-group-bottom">
            @if (isset($settings['whatsapp_active']) &&
                    $settings['whatsapp_active']->value == 1 &&
                    isset($settings['whatsapp_number']))
                <a href="https://wa.me/{{ $settings['whatsapp_number']->value }}?text=Merhaba,%20{{ $product->title }}%20ürünü%20hakkında%20bilgi%20almak%20istiyorum."
                    target="_blank" class="btn-whatsapp">
                    <!--svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </!--svg-->
                    WhatsApp
                </a>
            @endif
        </div>
    </div>
</div>
