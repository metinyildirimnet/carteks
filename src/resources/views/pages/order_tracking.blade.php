@extends('layouts.app')

@section('title', 'Sipariş Takip')

@section('content')
    <div class="container order-tracking-page-container">
        <div class="order-tracking-container">
            <h1 class="page-title">Sipariş Takip</h1>

            <div class="card order-tracking-form-card">
                <form action="{{ route('order.tracking') }}" method="GET">
                    <div class="form-group">
                        <label for="order_code">Sipariş Kodunuzu Girin:</label>
                        <input type="text" id="order_code" name="order_code" class="form-control order-code-input" placeholder="Örn: ABCDEF12" value="{{ request('order_code') }}" required>
                    </div>
                    <button type="submit" class="btn-primary btn-full-width">Siparişi Sorgula</button>
                </form>
            </div>

            @if (isset($order))
                <div class="card order-details-card">
                    <h2 class="section-title">Sipariş Detayları</h2>
                    <p><strong>Sipariş Kodu:</strong> {{ $order->order_code }}</p>
                    <p><strong>Müşteri Adı:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Telefon:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Adres:</strong> {{ $order->customer_address }}, {{ $order->customer_district }} / {{ $order->customer_city }}</p>
                    <p><strong>Sipariş Durumu:</strong> <span class="badge" style="background-color: {{ $order->status->color ?? '#6c757d' }}; color: #fff;">{{ $order->status->name ?? 'Bilinmiyor' }}</span></p>
                    <p><strong>Toplam Tutar:</strong> {{ number_format($order->total_amount, 2) }} ₺</p>
                    <p><strong>Sipariş Tarihi:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>

                    <h3 class="section-subtitle">Sipariş Edilen Ürünler</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($order->items as $item)
                            <li class="list-group-item">
                                @if ($item->product && $item->product->images->first())
                                    <img src="{{ asset($item->product->images->first()->image_path) }}" alt="{{ $item->product->title }}" class="product-image-small">
                                @else
                                    <img src="https://via.placeholder.com/50x50.png?text=No+Image" alt="No Image" class="product-image-small">
                                @endif
                                <div class="product-info-small">
                                    @if ($item->product)
                                        <a href="{{ $item->product->url }}">{{ $item->product->title }}</a>
                                    @else
                                        Ürün Bulunamadı (ID: {{ $item->product_id }})
                                    @endif
                                    <span>- {{ $item->quantity }} Adet x {{ number_format($item->price, 2) }} ₺</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @elseif (isset($errorMessage))
                <div class="card error-message-card">
                    <p>{{ $errorMessage }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
