@extends('layouts.app')

@section('title', 'Siparişi Tamamla')

@push('styles')
    <style>
        .quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            overflow: hidden;
            width: -moz-fit-content;
            width: fit-content;
        }

        .quantity-btn {
            background-color: #f8f9fa;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .quantity-btn:hover {
            background-color: #e9ecef;
        }

        .quantity-input {
            width: 30px;
            text-align: center;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            font-size: 14px;
            -moz-appearance: textfield;
        }

        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .cart-item-details {
            flex-grow: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        input,
        textarea {
            margin-top: 5px;
        }

        .recommended-products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .recommended-product-item {
            flex: 0 0 calc(50% - 8px);
        }
    </style>
@endpush

@section('content')
    <div class="container" style="margin-bottom: 50px;">
        <div class="checkout-container" style="max-width: 600px; margin: auto;">
            <h1 style="text-align: left; margin-bottom: 5px;">Siparişi Tamamla</h1>
            <p style="margin-top: 0; margin-bottom: 30px; color: #777;">Bilgilerinizi girin ve siparişinizi tamamlayın.</p>

            <div class="card" style="background-color: #e7f3fe; padding: 0px; border-radius: 8px; margin-bottom: 30px;">
                <div class="cart-header"
                    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; margin-bottom: 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <h2 style="font-size: 20px; margin: 0; display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" style="margin-right: 10px;">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        Sepetim
                    </h2>
                    <span id="cart-item-count"
                        style="color: #fff !important; font-size: 16px; color: #555;">{{ $cartItems->count() }}
                        ürün</span>
                </div>

                <div id="cart-items-container">
                    @if ($cartItems && $cartItems->isNotEmpty())
                        @foreach ($cartItems as $item)
                            <div class="cart-item card" data-product-id="{{ $item['product_id'] }}"
                                style="display: flex; align-items: center; margin-bottom: 20px; background-color: #fff; padding: 5px; border-radius: 8px;">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                <div class="cart-item-details">
                                    <div class="cart-item-name-wrapper">
                                        <h3 style="font-size: 14px; margin: 0;">{{ $item['name'] }}</h3>
                                    </div>
                                    <div class="cart-item-qty-price-wrapper">
                                        <div class="quantity-selector">
                                            <button class="quantity-btn btn-minus">-</button>
                                            <input type="number" class="quantity-input" value="{{ $item['quantity'] }}"
                                                min="1">
                                            <button class="quantity-btn btn-plus">+</button>
                                        </div>
                                        <div class="item-subtotal"
                                            style="font-size: 15px; font-weight: bold; width: 100px; margin-top:5px;">
                                            {{ number_format($item['price'] * $item['quantity'], 2) }} ₺
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Sepetiniz boş.</p>
                    @endif
                </div>

                <div id="cart-totals-container" style="@if ($cartItems->isEmpty()) display: none; @endif">
                    <div class="totals"
                        style="border-top: 1px solid #a7b9c9; padding: 10px; margin-top: 20px; font-size: 14px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Ara Toplam</span>
                            <span id="cart-subtotal">{{ number_format($cartTotal, 2) }} ₺</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Kargo</span>
                            <span style="color: #28a745;">Ücretsiz</span>
                        </div>
                        <div id="discount-line"
                            style="display: none; justify-content: space-between; margin-bottom: 10px; color: #28a745;">
                            <span>Havale İndirimi</span>
                            <span id="discount-amount"></span>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; border-top: 1px solid #a7b9c9; padding-top: 10px;">
                            <span>Genel Toplam</span>
                            <span id="cart-grand-total"
                                style="color: rgba(5, 95, 24, 0.733)">{{ number_format($cartTotal, 2) }} ₺</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($recommendedProducts->isNotEmpty())
                <div class="card" style="background-color: #fff; padding: 0px; border-radius: 8px; margin-bottom: 30px;">

                    <div class="cart-header"
                        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; margin-bottom: 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                        <h2 style="font-size: 20px; margin: 0; display: flex; align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="margin-right: 10px;">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg>
                            Bunları da Beğenebilirsiniz
                        </h2>
                    </div>
                    <div class="recommended-products-grid" style="padding: 0px 15px">
                        @foreach ($recommendedProducts as $product)
                            <div class="recommended-product-item">
                                <div class="card product-card h-100"
                                    style="display: flex; align-content: center; justify-content: center; align-items: center; flex-direction: column;">
                                    <a href="{{ $product->url }}">
                                        <img src="{{ $product->images->first()->image_path }}" class="card-img-top"
                                            alt="{{ $product->title }}" style="height: 100px; object-fit: cover;">
                                    </a>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"
                                            style="font-size: 1rem; margin-bottom: 0.5rem; text-align: center;">
                                            {{ $product->title }}</h5>
                                        <p class="card-text font-weight-bold"
                                            style="font-size: 1.1rem; text-align: center;">
                                            {{ number_format($product->price, 2) }} ₺</p>
                                        <button class="btn btn-sm btn-primary add-to-cart-btn-checkout mt-auto w-100"
                                            style="width:100%" data-product-id="{{ $product->id }}">Sepete Ekle</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card" style="padding: 0px 0px 10px 0px; border-radius: 8px;">
                <div class="cart-header"
                    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; margin-bottom: 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <h2 style="font-size: 20px; margin: 0; display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" style="margin-right: 10px;">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        Teslimat Bilgileri
                    </h2>
                </div>
                <div id="general-message" style="display:none; margin: 15px; padding: 10px; boder-radius: .5em;">
                </div>
                <form action="{{ route('checkout.placeOrder') }}" method="POST" style="padding:0px 10px;">
                    @csrf
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="name">Ad Soyad</label>
                        <input type="text" id="name" name="name" class="form-control">
                        <div class="text-danger" id="name-error"></div>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="phone">Telefon</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                        <div class="text-danger" id="phone-error"></div>
                    </div>
                    <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="city">İl</label>
                            <input type="text" id="city" name="city" class="form-control">
                            <div class="text-danger" id="city-error"></div>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="district">İlçe</label>
                            <input type="text" id="district" name="district" class="form-control">
                            <div class="text-danger" id="district-error"></div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="address">Teslimat Adresi</label>
                        <textarea id="address" name="address" class="form-control" rows="3"></textarea>
                        <div class="text-danger" id="address-error"></div>
                    </div>

                    <div class="payment-methods" style="">
                        <div class="payment-method-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery"
                                value="cash_on_delivery" checked>
                            <label class="form-check-label" for="cash_on_delivery"
                                style="display: flex; align-items: center !important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                    </rect>
                                    <circle cx="8" cy="12" r="2"></circle>
                                    <path d="M6 12h.01"></path>
                                    <path d="M18 12h.01"></path>
                                </svg>
                                Kapıda Ödeme (Nakit)
                            </label>
                        </div>
                        <div class="payment-method-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="card_on_delivery"
                                value="card_on_delivery">
                            <label class="form-check-label" for="card_on_delivery"
                                style="display: flex; align-items: center !important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                    </rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Kapıda Ödeme (Kredi Kartı)
                            </label>
                        </div>
                        @if (\App\Models\Module::isActive('bank_transfer'))
                        <div class="payment-method-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer"
                                value="bank_transfer">
                            <label class="form-check-label"
                                for="bank_transfer"style="display: flex; align-items: center !important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                    <path d="M2 17l10 5 10-5"></path>
                                    <path d="M2 12l10 5 10-5"></path>
                                </svg>
                                Havale / EFT
                                @if ($bankTransferDiscount > 0)
                                    <span class="badge badge-success ml-2"> %{{ $bankTransferDiscount }} indirim</span>
                                @endif
                            </label>
                        </div>
                        @endif
                        <div class="payment-method-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card"
                                value="credit_card" disabled>
                            <label class="form-check-label"
                                for="credit_card"style="display: flex; align-items: center !important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                    </rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Online Kredi Kartı
                                <span class="badge badge-error ml-2">Çok Yakında</span>
                            </label>
                        </div>
                        <div class="text-danger" id="payment_method-error"></div>
                    </div>

                    <div style="text-align: center; margin-top: 10px;">
                        <button type="submit" class="btn-primary btn-full-width"
                            style="padding: 15px 40px; font-size: 18px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="margin-right: 10px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Siparişi Onayla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        let originalCartTotal = {{ $cartTotal }}; // Original total from backend
        const bankTransferDiscountPercentage = {{ $bankTransferDiscount }}; // Discount percentage from backend

        // Debounce function from original script
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            // --- START: QUANTITY SELECTOR LOGIC ---

            const cartItemsContainer = document.getElementById('cart-items-container');

            // Using event delegation for dynamically added/removed items
            cartItemsContainer.addEventListener('click', function(event) {
                const target = event.target;

                if (target.classList.contains('btn-plus') || target.classList.contains('btn-minus')) {
                    const cartItem = target.closest('.cart-item');
                    const input = cartItem.querySelector('.quantity-input');
                    let currentQuantity = parseInt(input.value, 10);

                    if (target.classList.contains('btn-plus')) {
                        currentQuantity++;
                    } else if (target.classList.contains('btn-minus') && currentQuantity >
                        0) { // Allow reducing to 0 to remove
                        currentQuantity--;
                    }

                    input.value = currentQuantity;
                    debouncedUpdateCart(cartItem, currentQuantity);
                }
            });

            cartItemsContainer.addEventListener('change', function(event) {
                if (event.target.classList.contains('quantity-input')) {
                    const cartItem = event.target.closest('.cart-item');
                    let currentQuantity = parseInt(event.target.value, 10);
                    if (isNaN(currentQuantity) || currentQuantity < 0) {
                        currentQuantity = 0; // Default to 0 if invalid input
                    }
                    event.target.value = currentQuantity;
                    debouncedUpdateCart(cartItem, currentQuantity);
                }
            });

            async function updateCart(cartItem, quantity) {
                const productId = cartItem.dataset.productId;

                // Show loading indicator (optional)
                cartItem.style.opacity = '0.5';

                try {
                    const response = await fetch("{{ route('cart.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Sepet güncellenemedi.');
                    }

                    // Update UI based on response
                    updateCartUI(data);

                } catch (error) {
                    console.error('Cart update error:', error);
                    // Optionally revert quantity or show an error message
                    alert(error.message);
                } finally {
                    // Hide loading indicator
                    cartItem.style.opacity = '1';
                }
            }

            const debouncedUpdateCart = debounce(updateCart, 500);

            function updateCartUI(data) {
                const {
                    cart,
                    total,
                    count
                } = data;

                // If cart is empty, reload the page to show the "empty cart" view
                if (count === 0) {
                    window.location.reload();
                    return;
                }

                // Update totals
                const subtotalEl = document.getElementById('cart-subtotal');
                const grandTotalEl = document.getElementById('cart-grand-total');
                const countEl = document.getElementById('cart-item-count');

                const formattedTotal = total.toLocaleString('tr-TR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                if (subtotalEl) subtotalEl.textContent = `${formattedTotal} ₺`;

                // Update the originalCartTotal with the new total
                originalCartTotal = total;

                // Update grand total based on selected payment method
                updateGrandTotalDisplay(total); // Call this with the new base total
                if (countEl) countEl.textContent = `${count} ürün`;

                // Clear existing items
                cartItemsContainer.innerHTML = '';

                // Re-render cart items
                cart.forEach(itemData => {
                    const itemDiv = document.createElement('div');
                    itemDiv.classList.add('cart-item', 'card');
                    itemDiv.dataset.productId = itemData.product_id;
                    itemDiv.style.display = 'flex';
                    itemDiv.style.alignItems = 'center';
                    itemDiv.style.marginBottom = '20px';
                    itemDiv.style.backgroundColor = '#fff';
                    itemDiv.style.padding = '5px';
                    itemDiv.style.borderRadius = '8px';

                    itemDiv.innerHTML = `
                        <img src="${itemData.image}" alt="${itemData.name}"
                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                <div class="cart-item-details">
                                    <div class="cart-item-name-wrapper">
                                        <h3 style="font-size: 14px; margin: 0;">${itemData.name}</h3>
                                    </div>
                                    <div class="cart-item-qty-price-wrapper">
                                        <div class="quantity-selector">
                                            <button class="quantity-btn btn-minus">-</button>
                                            <input type="number" class="quantity-input" value="${itemData.quantity}"
                                                min="1">
                                            <button class="quantity-btn btn-plus">+</button>
                                        </div>
                                        <div class="item-subtotal"
                                            style="font-size: 15px; font-weight: bold; width: 100px; margin-top:5px;">${ (itemData.price * itemData.quantity).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) } ₺
                                        </div>
                                    </div>
                                </div>
                    `;
                    cartItemsContainer.appendChild(itemDiv);
                });
            }

            // --- END: QUANTITY SELECTOR LOGIC ---


            // --- START: PAYMENT METHOD & DISCOUNT LOGIC ---
            let currentOrderId = null; // Global variable to store the incomplete order ID

            // Payment method selection logic
            const paymentOptions = document.querySelectorAll('.payment-method-option');
            const cartGrandTotalSpan = document.getElementById('cart-grand-total');
            const discountLine = document.getElementById('discount-line');
            const discountAmountSpan = document.getElementById('discount-amount');

            function formatCurrency(amount) {
                return `${amount.toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ₺`;
            }

            function updateGrandTotalDisplay(baseTotal) {
                let finalTotal = baseTotal;
                let discountAmount = 0;
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');

                if (selectedPaymentMethod && selectedPaymentMethod.value === 'bank_transfer' &&
                    bankTransferDiscountPercentage > 0) {
                    discountAmount = baseTotal * (bankTransferDiscountPercentage / 100);
                    finalTotal = baseTotal - discountAmount;
                    discountAmountSpan.textContent = `- ${formatCurrency(discountAmount)}`;
                    discountLine.style.display = 'flex';
                } else {
                    discountLine.style.display = 'none';
                }
                cartGrandTotalSpan.textContent = formatCurrency(finalTotal);
            }

            function setActivePaymentOption() {
                paymentOptions.forEach(option => {
                    const radio = option.querySelector('input[type="radio"]');
                    if (radio.checked) {
                        option.classList.add('selected');
                    } else {
                        option.classList.remove('selected');
                    }
                });
                // Always update grand total display when payment option changes
                updateGrandTotalDisplay(originalCartTotal);
            }

            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (!radio.disabled) { // Check if the radio button is not disabled
                        radio.checked = true;
                        setActivePaymentOption();
                    }
                });
            });

            // Set initial state and update grand total display
            setActivePaymentOption();
            // Initial call with original total is handled by setActivePaymentOption now.

            // --- END: PAYMENT METHOD & DISCOUNT LOGIC ---


            // --- Original script from here ---
            // Phone number formatting logic
            const phoneInput = document.getElementById('phone');
            const nameInput = document.getElementById('name'); // Get name input
            const prefix = '+90';

            const formatPhoneNumber = (value) => {
                let numbers = value.replace(/\D/g, '');
                if (numbers.startsWith('90')) {
                    numbers = numbers.substring(2);
                }

                if (numbers.length > 0 && numbers.charAt(0) !== '5') {
                    numbers = '5' + numbers.substring(1);
                }

                let formatted = prefix;
                if (numbers.length > 0) {
                    formatted += ' ' + numbers.substring(0, 3);
                }
                if (numbers.length > 3) {
                    formatted += ' ' + numbers.substring(3, 6);
                }
                if (numbers.length > 6) {
                    formatted += ' ' + numbers.substring(6, 8);
                }
                if (numbers.length > 8) {
                    formatted += ' ' + numbers.substring(8, 10);
                }
                return formatted;
            };

            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value;
                if (value.length < prefix.length) {
                    e.target.value = prefix;
                    return;
                }
                e.target.value = formatPhoneNumber(value);
            });

            phoneInput.addEventListener('keydown', function(e) {
                if (e.target.selectionStart <= prefix.length && (e.key === 'Backspace' || e.key ===
                        'Delete')) {
                    e.preventDefault();
                }
            });

            // Initial formatting
            if (phoneInput.value.length <= prefix.length) {
                phoneInput.value = prefix;
            }

            // AJAX Form Submission Logic
            const checkoutForm = document.querySelector('form');
            const submitButton = checkoutForm.querySelector('button[type="submit"]');
            const errorDivs = document.querySelectorAll('.text-danger');
            const generalMessageDiv = document.getElementById('general-message');

            function clearErrors() {
                errorDivs.forEach(div => div.textContent = '');
                generalMessageDiv.textContent = '';
                generalMessageDiv.className = ''; // Clear any previous styling
            }

            function showGeneralMessage(message, type = 'error') {
                generalMessageDiv.style.display = 'block';
                generalMessageDiv.textContent = message;
                generalMessageDiv.className = `alert alert-${type}`; // Add Bootstrap alert classes
            }

            // Function to create/update incomplete order
            const createOrUpdateIncompleteOrder = async () => {
                const name = nameInput.value.trim();
                const phone = phoneInput.value.trim();

                if (name.length > 0 && phone.length > prefix.length) {
                    const formData = new FormData();
                    formData.append('name', name);
                    formData.append('phone', phone);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'));

                    try {
                        const response = await fetch("{{ route('checkout.createIncompleteOrder') }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok) {
                            currentOrderId = data.order_id;
                            console.log('Incomplete order ID:', currentOrderId);
                        } else {
                            // Handle errors for incomplete order creation
                            console.error('Failed to create incomplete order:', data.message);
                            // Optionally show a message to the user
                            // showGeneralMessage(data.message || 'Geçici sipariş oluşturulurken bir hata oluştu.', 'error');
                        }
                    } catch (error) {
                        console.error('Network error during incomplete order creation:', error);
                        // showGeneralMessage('Ağ hatası: Geçici sipariş oluşturulamadı.', 'error');
                    }
                }
            };

            // Debounced version of the incomplete order creation function
            const debouncedCreateOrUpdateIncompleteOrder = debounce(createOrUpdateIncompleteOrder, 1000);

            // Listen for input on name and phone fields to trigger incomplete order creation
            nameInput.addEventListener('input', debouncedCreateOrUpdateIncompleteOrder);
            phoneInput.addEventListener('input', debouncedCreateOrUpdateIncompleteOrder);


            checkoutForm.addEventListener('submit', async function(event) {
                event.preventDefault(); // Prevent default form submission
                clearErrors(); // Clear previous errors and messages

                if (!currentOrderId) {
                    showGeneralMessage(
                        'Lütfen ad soyad ve telefon bilgilerinizi girerek siparişinizi başlatın.',
                        'error');
                    return;
                }

                submitButton.disabled = true;
                submitButton.textContent = 'Sipariş Oluşturuluyor...';

                const formData = new FormData(checkoutForm);
                formData.append('order_id', currentOrderId); // Add the incomplete order ID

                const response = await fetch(checkoutForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Important for Laravel to detect AJAX
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                submitButton.disabled = false;
                submitButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" style="margin-right: 10px;">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Siparişi Onayla
                `;

                if (response.ok) {
                    const data = await response.json();
                    if (data.redirect) {
                        window.location.href = data.redirect; // Redirect to success page
                    } else {
                        showGeneralMessage(data.message, 'success');
                    }
                } else if (response.status === 422) {
                    const errors = await response.json();
                    for (const field in errors.errors) {
                        const errorElement = document.getElementById(`${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = errors.errors[field][0];
                        }
                    }
                    showGeneralMessage('Lütfen formdaki hataları düzeltin.', 'error');
                } else {
                    // Handle other types of errors (e.g., 500 server error, or 400 from empty cart)
                    const errorData = await response.json();
                    showGeneralMessage(errorData.message ||
                        'Sipariş oluşturulurken bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                }
            });

            // Clear error when user types in an input field
            checkoutForm.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', function() {
                    const fieldName = this.name;
                    const errorElement = document.getElementById(`${fieldName}-error`);
                    if (errorElement) {
                        errorElement.textContent = '';
                    }
                    // Clear general message if user starts typing after a general error
                    if (generalMessageDiv.textContent && generalMessageDiv.className.includes(
                            'alert-error')) {
                        clearErrors();
                    }
                });
            });

            // Add to cart for recommended products
            const recommendedProductsGrid = document.querySelector('.recommended-products-grid');
            if (recommendedProductsGrid) {
                recommendedProductsGrid.addEventListener('click', function(event) {
                    if (event.target.classList.contains('add-to-cart-btn-checkout')) {
                        const productId = event.target.dataset.productId;
                        addToCart(productId);
                    }
                });
            }

            async function addToCart(productId) {
                try {
                    const response = await fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Ürün sepete eklenemedi.');
                    }

                    // This function is defined in the main script block above
                    updateCartUI(data);

                } catch (error) {
                    console.error('Add to cart error:', error);
                    alert(error.message);
                }
            }
        });
    </script>
@endsection
