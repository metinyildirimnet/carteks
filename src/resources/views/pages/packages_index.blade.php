@extends('layouts.app')

@section('title', 'Tüm Paketler')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="my-4">Tüm Paketler</h1>
            </div>
        </div>

        <div class="row mb-4">
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

        <div id="packages-container">
            <div class="products-grid" style="margin-bottom: 20px;">
                @forelse($packages as $product)
                    @include('partials._product_card', ['product' => $product])
                @empty
                    <div class="col-12">
                        <p>Henüz hiç paket eklenmemiş.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $packages->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtonsContainer = document.querySelector('.usage-areas-filter-buttons');
            const clearFiltersBtn = document.getElementById('clear-filters-btn');
            const packagesContainer = document.getElementById('packages-container'); // Changed ID
            const productsGrid = packagesContainer.querySelector('.products-grid');
            const paginationContainer = packagesContainer.querySelector('.d-flex.justify-content-center.mt-4');

            console.log('DOM Content Loaded for Packages');
            console.log('filterButtonsContainer:', filterButtonsContainer);
            console.log('clearFiltersBtn:', clearFiltersBtn);
            console.log('packagesContainer:', packagesContainer);

            function fetchPackages(selectedAreas, page = 1) { // Changed function name
                console.log('fetchPackages called with selectedAreas:', selectedAreas, 'page:', page);
                const params = new URLSearchParams();
                selectedAreas.forEach(area => params.append('usage_areas[]', area));
                if (page) {
                    params.append('page', page);
                }

                fetch(`{{ route('packages.index') }}?${params.toString()}`, { // Changed route
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
                .catch(error => console.error('Error fetching packages:', error)); // Changed error message
            }

            if (filterButtonsContainer) {
                filterButtonsContainer.addEventListener('click', function(event) {
                    console.log('Click event on filterButtonsContainer for Packages:', event.target);
                    if (event.target.classList.contains('usage-area-button')) {
                        event.target.classList.toggle('selected');

                        const selectedAreas = Array.from(filterButtonsContainer.querySelectorAll('.usage-area-button.selected'))
                                                .map(button => button.dataset.value);
                        console.log('Selected areas after click for Packages:', selectedAreas);
                        fetchPackages(selectedAreas); // Changed function call
                    }
                });
            } else {
                console.error('filterButtonsContainer not found for Packages!');
            }

            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    console.log('Clear filters button clicked for Packages');
                    filterButtonsContainer.querySelectorAll('.usage-area-button.selected')
                                        .forEach(button => button.classList.remove('selected'));
                    fetchPackages([]); // Changed function call
                });
            } else {
                console.error('clearFiltersBtn not found for Packages!');
            }


            // Handle pagination clicks (delegation)
            if (packagesContainer) { // Changed ID
                packagesContainer.addEventListener('click', function(event) {
                    if (event.target.matches('.pagination a')) {
                        event.preventDefault();
                        console.log('Pagination link clicked for Packages:', event.target.href);
                        const url = new URL(event.target.href);
                        const page = url.searchParams.get('page');

                        const selectedAreas = Array.from(filterButtonsContainer.querySelectorAll('.usage-area-button.selected'))
                                                .map(button => button.dataset.value);

                        fetchPackages(selectedAreas, page); // Changed function call
                        window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll to top after pagination
                    }
                });
            } else {
                console.error('packagesContainer not found for pagination for Packages!');
            }
        });
    </script>
@endsection
