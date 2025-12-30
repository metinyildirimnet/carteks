@extends('layouts.app')

@section('title', 'Tüm Ürünler')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="my-4">Tüm Ürünler</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="usage-area card">
                    <div class="usage-areas-title">Kullanım Alanları</div>
                    <div class="usage-areas-filter-buttons-container">
                        <button type="button" class="btn btn-secondary btn-sm clear-filter-icon-btn" id="clear-filters-btn">
                            &times;
                        </button>
                        <div class="usage-areas-filter-buttons">
                            @foreach($usageAreas as $area)
                                <button type="button" class="usage-area-button {{ in_array($area, $selectedUsageAreas) ? 'selected' : '' }}" data-value="{{ $area }}">
                                    {{ $area }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="products-container">
            <div class="products-grid" style="margin-bottom: 20px;">
                @forelse($products as $product)
                    @include('partials._product_card', ['product' => $product])
                @empty
                    <div class="col-12">
                        <p>Henüz hiç ürün eklenmemiş.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtonsContainer = document.querySelector('.usage-areas-filter-buttons');
            const clearFiltersBtn = document.getElementById('clear-filters-btn');
            const productsContainer = document.getElementById('products-container');
            const productsGrid = productsContainer.querySelector('.products-grid');
            const paginationContainer = productsContainer.querySelector('.d-flex.justify-content-center.mt-4');

            console.log('DOM Content Loaded');
            console.log('filterButtonsContainer:', filterButtonsContainer);
            console.log('clearFiltersBtn:', clearFiltersBtn);
            console.log('productsContainer:', productsContainer);

            function fetchProducts(selectedAreas, page = 1) {
                console.log('fetchProducts called with selectedAreas:', selectedAreas, 'page:', page);
                const params = new URLSearchParams();
                selectedAreas.forEach(area => params.append('usage_areas[]', area));
                if (page) {
                    params.append('page', page);
                }

                fetch(`{{ route('products.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('AJAX response received:', response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('AJAX data parsed:', data);
                    productsGrid.innerHTML = data.product_grid_html;
                    paginationContainer.innerHTML = data.pagination_html;
                })
                .catch(error => console.error('Error fetching products:', error));
            }

            if (filterButtonsContainer) {
                filterButtonsContainer.addEventListener('click', function(event) {
                    console.log('Click event on filterButtonsContainer:', event.target);
                    if (event.target.classList.contains('usage-area-button')) {
                        event.target.classList.toggle('selected');

                        const selectedAreas = Array.from(filterButtonsContainer.querySelectorAll('.usage-area-button.selected'))
                                                .map(button => button.dataset.value);
                        console.log('Selected areas after click:', selectedAreas);
                        fetchProducts(selectedAreas);
                    }
                });
            } else {
                console.error('filterButtonsContainer not found!');
            }

            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    console.log('Clear filters button clicked');
                    filterButtonsContainer.querySelectorAll('.usage-area-button.selected')
                                        .forEach(button => button.classList.remove('selected'));
                    fetchProducts([]);
                });
            } else {
                console.error('clearFiltersBtn not found!');
            }


            // Handle pagination clicks (delegation)
            if (productsContainer) {
                productsContainer.addEventListener('click', function(event) {
                    if (event.target.matches('.pagination a')) {
                        event.preventDefault();
                        console.log('Pagination link clicked:', event.target.href);
                        const url = new URL(event.target.href);
                        const page = url.searchParams.get('page');

                        const selectedAreas = Array.from(filterButtonsContainer.querySelectorAll('.usage-area-button.selected'))
                                                .map(button => button.dataset.value);

                        fetchProducts(selectedAreas, page); // Pass page to fetchProducts
                        window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll to top after pagination
                    }
                });
            } else {
                console.error('productsContainer not found for pagination!');
            }
        });
    </script>
@endsection
