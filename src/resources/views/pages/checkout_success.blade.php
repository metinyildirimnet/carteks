@extends('layouts.app')

@section('title', 'Sipariş Başarılı')

@section('content')
    <div class="container" style="margin-bottom: 50px; padding: 50px 0;">
        <div class="checkout-container" style="max-width: 800px; margin: auto;">
            @if (isset($order) && $order)
                <div class="card"
                    style="padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none"
                        stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="margin-bottom: 20px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-8.93"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <h1 style="color: #28a745; margin-bottom: 15px;">Siparişiniz Başarıyla Alındı!</h1>
                    <p style="font-size: 18px; color: #555; margin-bottom: 25px;">Sipariş numaranız:
                        <strong>{{ $order->order_code }}</strong>
                    </p>
                    <p style="font-size: 16px; color: #777; margin-bottom: 30px;">Teşekkür ederiz. Siparişiniz işleme
                        alınmıştır ve en kısa sürede kargoya verilecektir.</p>
                    <a href="{{ route('home') }}" class="btn-primary"
                        style="padding: 10px 25px; font-size: 16px; text-decoration: none; border-radius: 5px;">Ana Sayfaya
                        Dön</a>
                </div>

                @if ($order && $order->payment_method === 'bank_transfer' && \App\Models\Module::isActive('bank_transfer'))
                    @php
                        $bankAccounts = \App\Models\Module::getSettings('bank_transfer', 'accounts', []);
                    @endphp
                    @if (!empty($bankAccounts))
                    <div class="card" style="margin-top: 30px; padding: 20px; border-radius: 8px; border: 2px solid #007bff;">
                        <h2 style="font-size: 22px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">
                            Banka Hesap Bilgileri
                        </h2>
                        <p>Lütfen aşağıdaki banka hesabına <strong>{{ number_format($order->total_amount, 2) }} ₺</strong> tutarındaki sipariş ödemenizi gerçekleştirin. Ödemeniz onaylandıktan sonra siparişiniz kargoya verilecektir.</p>

                        @foreach($bankAccounts as $account)
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #ddd;">
                            <p style="margin: 5px 0;"><strong>Banka Adı:</strong> {{ $account['bank_name'] }}</p>
                            <p style="margin: 5px 0;"><strong>Hesap Sahibi:</strong> {{ $account['account_holder'] }}</p>
                            <p style="margin: 5px 0;"><strong>IBAN:</strong> {{ $account['iban'] }}</p>
                            @if(!empty($account['description']))
                            <p class="text-muted" style="margin: 5px 0;"><strong>Açıklama:</strong> {{ $account['description'] }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                @endif

                <div class="card" style="margin-top: 30px; padding: 20px; border-radius: 8px;">
                    <h2 style="font-size: 22px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">
                        Sipariş Özeti</h2>
                    @foreach ($order->items as $item)
                        <div class="order-item"
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #eee;">
                            <div style="display: flex; align-items: center;">
                                @if ($item->product && $item->product->images->isNotEmpty())
                                    <img src="{{ $item->product->images->first()->image_path }}"
                                        alt="{{ $item->product->title }}"
                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 15px;">
                                @endif
                                <div>
                                    @if ($item->product)
                                        <h3 style="font-size: 16px; margin: 0;">{{ $item->product->title }}</h3>
                                    @else
                                        <h3 style="font-size: 16px; margin: 0;">Ürün Bulunamadı (ID:
                                            {{ $item->product_id }})</h3>
                                    @endif
                                    <p style="margin: 5px 0 0; color: #777;">{{ $item->quantity }} Adet x
                                        {{ number_format($item->price, 2) }} ₺</p>
                                </div>
                            </div>
                            <div style="font-size: 16px; font-weight: bold;">
                                {{ number_format($item->quantity * $item->price, 2) }} ₺</div>
                        </div>
                    @endforeach

                    <div class="totals"
                        style="border-top: 1px solid #a7b9c9; padding-top: 20px; margin-top: 20px; font-size: 14px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Ara Toplam</span>
                            <span>{{ number_format($order->total_amount, 2) }} ₺</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Kargo</span>
                            <span style="color: #28a745;">Ücretsiz</span>
                        </div>
                        @if ($order->discount_amount > 0)
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #dc3545;">
                                <span>Uygulanan İndirim</span>
                                <span>-{{ number_format($order->discount_amount, 2) }} ₺</span>
                            </div>
                        @endif
                        @if ($order->discounts->isNotEmpty())
                            @foreach ($order->discounts as $discount)
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #dc3545;">
                                    <span>{{ $discount->description }}</span>
                                    <span>-{{ number_format($discount->amount, 2) }} ₺</span>
                                </div>
                            @endforeach
                        @endif
                        <div
                            style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; border-top: 1px solid #a7b9c9; padding-top: 10px;">
                            <span>Genel Toplam</span>
                            <span style="color: rgba(5, 95, 24, 0.733)">{{ number_format($order->total_amount, 2) }}
                                ₺</span>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top: 30px; padding: 20px; border-radius: 8px;">
                    <h2 style="font-size: 22px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">
                        Teslimat Bilgileri</h2>
                    <p><strong>Ad Soyad:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Telefon:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Adres:</strong> {{ $order->customer_address }}, {{ $order->customer_district }} /
                        {{ $order->customer_city }}</p>
                    <p><strong>Ödeme Yöntemi:</strong>
                        @php
                            $paymentMethods = [
                                'cash_on_delivery' => 'Kapıda Ödeme (Nakit)',
                                'card_on_delivery' => 'Kapıda Ödeme (Kredi Kartı)',
                                'bank_transfer' => 'Havale / EFT',
                                'credit_card' => 'Online Kredi Kartı',
                            ];
                            echo $paymentMethods[$order->payment_method] ??
                                ucfirst(str_replace('_', ' ', $order->payment_method));
                        @endphp
                    </p>
                </div>
            @else
                <div class="card"
                    style="padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                    <h1 style="color: #dc3545; margin-bottom: 15px;">Sipariş Bulunamadı!</h1>
                    <p style="font-size: 18px; color: #555; margin-bottom: 25px;">Geçerli bir sipariş bulunamadı veya oturum
                        süresi dolmuş olabilir.</p>
                    <a href="{{ route('home') }}" class="btn-primary"
                        style="padding: 10px 25px; font-size: 16px; text-decoration: none; border-radius: 5px;">Ana Sayfaya
                        Dön</a>
                </div>
            @endif
        </div>
    </div>

    @if (isset($order) && isset($settings['facebook_pixel_id']) && $settings['facebook_pixel_id']->value)
        <script>
            fbq('track', 'Purchase', {
                value: {{ number_format($order->total_amount, 2, '.', '') }},
                currency: 'TRY',
                content_ids: [
                    @foreach ($order->items as $item)
                        '{{ $item->product_id }}',
                    @endforeach
                ],
                content_type: 'product',
                num_items: {{ $order->items->sum('quantity') }}
            });
        </script>
    @endif
@endsection
