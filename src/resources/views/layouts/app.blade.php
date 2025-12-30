<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Add CSRF token --}}
    <title>@yield('title', 'Anasayfa')</title>
    @if (isset($settings['facebook_pixel_id']) && $settings['facebook_pixel_id']->value)
        <!-- Facebook Pixel Base Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $settings['facebook_pixel_id']->value }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $settings['facebook_pixel_id']->value }}&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Base Code -->
    @endif
    <link rel="stylesheet" href="{{ asset('css/style.css?ver=' . rand(1, 2000)) }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> --}}
    <style>
        .toast-success {
            background-color: #fff !important;
            border-left: 5px solid #28a745 !important;
            color: #333 !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="marquee">
        <div class="marquee-container">
            <div class="marquee-content">
                @forelse ($marqueeItems as $item)
                    <span class="marquee-item text-sm md:text-base font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ $item->content }}
                    </span>
                    @if (!$loop->last)
                        <span class="marquee-separator">•••</span>
                    @endif
                @empty
                    <span class="marquee-item text-sm md:text-base font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Henüz kayan yazı öğesi bulunmuyor.
                    </span>
                @endforelse
            </div>
        </div>
    </div>
    <header>
        <div class="container nav-top">
            <div class="nav-left">
                <div class="logo">
                    @if (isset($settings['site_logo']))
                        <a href="{{ url('/') }}">
                            <img src="{{ $settings['site_logo']->value }}"
                                alt="{{ $settings['site_title']->value ?? 'Site Logo' }}" style="max-height: 40px;">
                        </a>
                    @else
                    @endif
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="{{ url('/') }}"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg> Anasayfa</a></li>
                        <li><a href="{{ url('/urunler') }}"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                    </path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg> Ürünler</a></li>
                        <li><a href="{{ route('packages.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 12 20 22 4 22 4 12"></polyline>
                                    <rect x="2" y="7" width="20" height="5"></rect>
                                    <line x1="12" y1="22" x2="12" y2="7"></line>
                                    <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                                    <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                                </svg> Paketler</a></li>
                        <li><a href="{{ route('order.tracking') }}"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg> Sipariş Takip</a></li>
                    </ul>
                </nav>
            </div>
            <div class="nav-right">
                <a href="#" id="cart-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span>Sepetim</span>
                </a>
            </div>
        </div>
    </header>



    <main>
        @yield('content')
    </main>

    <footer class="main-footer-redesign">
        <div class="container">
            <div class="footer-logo-section">
                @if (isset($settings['site_logo']))
                    <a href="{{ url('/') }}">
                        <img src="{{ $settings['site_logo']->value }}"
                            alt="{{ $settings['site_title']->value ?? 'Site Logo' }}" style="max-height: 60px;">
                    </a>
                @endif
            </div>

            <div class="footer-columns-grid">
                @if(isset($footerMenus))
                    @foreach($footerMenus as $group)
                        <div class="footer-col">
                            <h4>{{ $group->title }}</h4>
                            <ul>
                                @foreach($group->items as $item)
                                    <li><a href="{{ $item->getUrl() }}" target="{{ $item->target }}">{{ $item->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @endif
                <div class="footer-col footer-social">
                    <h4>Bizi Takip Edin</h4>
                    <div class="social-icons">
                        @if (isset($settings['social_facebook_url']) && $settings['social_facebook_url']->value)
                            <a href="{{ $settings['social_facebook_url']->value }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if (isset($settings['social_twitter_url']) && $settings['social_twitter_url']->value)
                            <a href="{{ $settings['social_twitter_url']->value }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if (isset($settings['social_instagram_url']) && $settings['social_instagram_url']->value)
                            <a href="{{ $settings['social_instagram_url']->value }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if (isset($settings['social_linkedin_url']) && $settings['social_linkedin_url']->value)
                            <a href="{{ $settings['social_linkedin_url']->value }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-copyright-section">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <span>© {{ date('Y') }} {{ $settings['site_title']->value ?? 'Cleanoxtr' }}. Tüm hakları
                saklıdır.</span>
            <span style="text-align: right;">
                <a href="https://cimendijital.com" target="_blank" style="text-decoration: none; color: inherit;">
                    Cimendijital
                </a>
            </span>
        </div>
    </div>

    <aside id="cart-sidebar">
        <div class="cart-header">
            <h3>Sepetim (<span id="cart-count">0</span>)</h3>
            <button id="cart-close">&times;</button>
        </div>
        <div id="cart-items-container" style="flex-grow: 1; overflow-y: auto;">
            {{-- Cart items will be rendered here by JavaScript --}}
            <p id="empty-cart-message" style="text-align: center; color: #666;">Sepetiniz boş</p>
        </div>
        <div class="cart-footer">
            <hr id="cart-summary-divider">
            <div id="cart-summary"
                style="display: flex; justify-content: space-between; font-size: 1.1em; font-weight: bold; margin-bottom: 15px; padding: 0px 10px;">
                <span>Toplam Tutar:</span>
                <span><span id="cart-total">0.00</span> ₺</span>
            </div>
            <a href="{{ route('checkout.show') }}" class="btn-primary"
                style="text-align: center; display: block; margin: 10px;">Siparişi
                Tamamla</a>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartToggle = document.getElementById('cart-toggle');
            const mobileCartToggle = document.getElementById('mobile-cart-toggle'); // New line
            const cartSidebar = document.getElementById('cart-sidebar');
            const cartClose = document.getElementById('cart-close');
            const cartItemsContainer = document.getElementById('cart-items-container');
            const cartTotalSpan = document.getElementById('cart-total');
            const cartCountSpan = document.getElementById('cart-count');
            const clearCartBtn = document.getElementById('clear-cart-btn');

            // CSRF Token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Event listener for desktop cart toggle
            cartToggle.addEventListener('click', function(e) {
                e.preventDefault();
                if (window.location.pathname === '/checkout') {
                    return;
                }
                cartSidebar.classList.toggle('open');
                fetchCartContents(); // Fetch latest cart data when opening
            });

            // Event listener for mobile cart toggle (New)
            if (mobileCartToggle) { // Check if the element exists
                mobileCartToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    cartSidebar.classList.toggle('open');
                    fetchCartContents(); // Fetch latest cart data when opening
                });
            }

            cartClose.addEventListener('click', function(e) {
                e.preventDefault();
                cartSidebar.classList.remove('open');
            });

            // Function to update cart sidebar UI
            async function updateCartSidebar(cartData) {
                console.log(cartData); // Log the cart data to the browser console

                const cartItemsContainer = document.getElementById('cart-items-container');
                const cartTotalSpan = document.getElementById('cart-total');
                const cartCountSpan = document.getElementById('cart-count');
                const cartSummary = document.getElementById('cart-summary');
                const cartSummaryDivider = document.getElementById('cart-summary-divider');

                cartItemsContainer.innerHTML = ''; // Clear existing items

                if (cartData.cart && cartData.cart.length > 0) {
                    // Show summary
                    cartSummaryDivider.style.display = 'block';

                    // Create and append cart items
                    cartData.cart.forEach(item => {
                        const itemDiv = document.createElement('div');
                        itemDiv.classList.add('cart-item');
                        itemDiv.style.display = 'flex';
                        itemDiv.style.alignItems = 'center';
                        itemDiv.style.marginBottom = '10px';
                        itemDiv.innerHTML = `
                            <img src="${item.image ? item.image : 'https://via.placeholder.com/50x50.png?text=No+Image'}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                            <div style="flex-grow: 1;">
                                <p style="margin: 0; font-weight: bold;">${item.name}</p>
                                <p style="margin: 0; font-size: 0.9em; color: #666;">${item.quantity} x ${item.price.toFixed(2)} ₺</p>
                            </div>
                            <button class="remove-from-cart-btn" data-product-id="${item.product_id}" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 1.2em;">&times;</button>
                        `;
                        cartItemsContainer.appendChild(itemDiv);
                    });
                } else {
                    // Show empty cart message and hide summary
                    cartItemsContainer.innerHTML =
                        '<p style="text-align: center; color: #666;">Sepetiniz boş</p>';
                    cartSummary.style.display = 'none';
                    cartSummaryDivider.style.display = 'none';
                }

                // Update total and count
                cartTotalSpan.textContent = cartData.total.toFixed(2);
                cartCountSpan.textContent = cartData.count;
            }

            // Function to fetch cart contents from backend
            async function fetchCartContents() {
                try {
                    const response = await fetch('{{ route('cart.get') }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    const data = await response.json();
                    updateCartSidebar(data);
                } catch (error) {
                    console.error('Sepet içeriği getirilirken hata oluştu:', error);
                }
            }

            // Function to add product to cart
            async function addToCart(productId, quantity = 1) {
                try {
                    const response = await fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    });
                    const data = await response.json();
                    if (response.ok) {
                        updateCartSidebar(data);
                        if (window.location.pathname !== '/checkout') {
                            cartSidebar.classList.add('open');
                        }
                        // Facebook Pixel AddToCart event
                        @if (isset($settings['facebook_pixel_id']) && $settings['facebook_pixel_id']->value)
                            fbq('track', 'AddToCart', {
                                content_ids: [productId], // Use the product ID
                                content_type: 'product',
                                value: data.total, // Use the new cart total as value
                                currency: 'TRY'
                            });
                        @endif
                        // toastr.success(data.message);
                    } else {
                        // toastr.error('Ürün sepete eklenirken hata oluştu.');
                        alert('Ürün sepete eklenirken hata oluştu.');
                    }
                } catch (error) {
                    console.error('Sepete eklerken AJAX hatası:', error);
                    // toastr.error('Sunucuya bağlanırken hata oluştu.');
                    alert('Sunucuya bağlanırken hata oluştu.');
                }
            }

            // Function to remove product from cart
            async function removeFromCart(productId) {
                if (!confirm('Bu ürünü sepetten kaldırmak istediğinizden emin misiniz?')) {
                    return;
                }
                try {
                    const response = await fetch('{{ route('cart.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    });
                    const data = await response.json();
                    if (response.ok) {
                        updateCartSidebar(data);
                        //alert(data.message);
                    } else {
                        alert('Ürün sepetten kaldırılırken hata oluştu.');
                    }
                } catch (error) {
                    console.error('Sepetten kaldırırken AJAX hatası:', error);
                    alert('Sunucuya bağlanırken hata oluştu.');
                }
            }

            // Function to clear cart
            async function clearCart() {
                if (!confirm('Sepeti tamamen temizlemek istediğinizden emin misiniz?')) {
                    return;
                }
                try {
                    const response = await fetch('{{ route('cart.clear') }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    const data = await response.json();
                    if (response.ok) {
                        updateCartSidebar(data);
                        alert(data.message);
                    } else {
                        alert('Sepet temizlenirken hata oluştu.');
                    }
                } catch (error) {
                    console.error('Sepet temizlenirken AJAX hatası:', error);
                    alert('Sunucuya bağlanırken hata oluştu.');
                }
            }


            // Event listener for "Add to Cart" buttons (delegated for dynamic content)
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-to-cart-btn')) {
                    e.preventDefault();
                    const productId = e.target.dataset.productId;
                    addToCart(productId, 1); // Add 1 quantity by default
                }
                if (e.target.classList.contains('remove-from-cart-btn')) {
                    e.preventDefault();
                    const productId = e.target.dataset.productId;
                    removeFromCart(productId);
                }
            });

            // Event listener for clear cart button
            clearCartBtn.addEventListener('click', clearCart);


            // Initialize cart on page load
            fetchCartContents();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                    dots[i].classList.remove('active');
                });

                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentSlide = index;
            }

            function nextSlide() {
                let newSlide = (currentSlide + 1) % slides.length;
                showSlide(newSlide);
            }

            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    const slideIndex = parseInt(this.getAttribute('data-slide'));
                    showSlide(slideIndex);
                    // Reset interval on manual navigation
                    clearInterval(slideInterval);
                    slideInterval = setInterval(nextSlide, 5000);
                });
            });

            // Start automatic sliding
            slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        });
    </script>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}
    {{-- <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script> --}}
    @yield('scripts')

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="#" class="mobile-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Anasayfa</span>
        </a>
        <a href="{{route('products.index')}}" class="mobile-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path
                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                </path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span>Ürünler</span>
        </a>
        <a href="{{ route('packages.index') }}" class="mobile-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="20 12 20 22 4 22 4 12"></polyline>
                <rect x="2" y="7" width="20" height="5"></rect>
                <line x1="12" y1="22" x2="12" y2="7"></line>
                <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
            </svg>
            <span>Paketler</span>
        </a>
        <a href="#" class="mobile-nav-item" id="mobile-cart-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <span>Sepetim</span>
        </a>
        <a href="{{ route('order.tracking') }}" class="mobile-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <rect x="1" y="3" width="15" height="13"></rect>
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                <circle cx="18.5" cy="18.5" r="2.5"></circle>
            </svg>
            <span>Sipariş</span>
        </a>
    </nav>
</body>

</html>
