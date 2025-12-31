@extends('admin.layouts.new_app')

@section('title', 'Ana Sayfa Bloğu Düzenle')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Ana Sayfa Bloğu Düzenle: {{ $homepageBlock->title }}</h3>
            </div>
            <form action="{{ route('admin.design.homepage-blocks.update', $homepageBlock->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.homepage_blocks._form')
            </form>
        </div>
    </div>
</div>

{{-- Product Sorting Section - Only show for product blocks --}}
@if($homepageBlock->type === 'product' && $homepageBlock->products->isNotEmpty())
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Seçili Ürünleri Sırala</h3>
            </div>
            <div class="card-body">
                <div id="reorder-status" class="alert" style="display: none;"></div>
                <ul id="sortable-products">
                    @foreach($homepageBlock->products as $product)
                        <li data-id="{{ $product->id }}">
                            <span class="sort-handle">☰</span>
                            {{ $product->title }}
                        </li>
                    @endforeach
                </ul>
                <small class="form-text text-muted">
                    Ürünleri sürükleyip bırakarak sıralamayı değiştirebilirsiniz.
                </small>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
    {{-- This is now included in the _form partial, but we add the sortable styles here --}}
    <style>
        #sortable-products { list-style-type: none; padding: 0; border: 1px solid #ddd; border-radius: 4px; }
        #sortable-products li { padding: 12px; background-color: #f9f9f9; border-bottom: 1px solid #ddd; cursor: move; display: flex; align-items: center; }
        #sortable-products li:last-child { border-bottom: none; }
        #sortable-products li:hover { background-color: #f1f1f1; }
        .sort-handle { margin-right: 10px; color: #999; }
    </style>
@endpush

@push('scripts')
    {{-- This is now included in the _form partial, but we add the sortable script here --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortableList = document.getElementById('sortable-products');
        if (sortableList) {
            const reorderStatusDiv = document.getElementById('reorder-status');
            new Sortable(sortableList, {
                animation: 150,
                handle: '.sort-handle',
                onEnd: function (evt) {
                    const productIds = Array.from(sortableList.children).map(li => li.dataset.id);
                    reorderStatusDiv.style.display = 'block';
                    reorderStatusDiv.className = 'alert alert-info';
                    reorderStatusDiv.textContent = 'Sıralama güncelleniyor...';

                    fetch("{{ route('admin.design.homepage-blocks.reorderProducts', $homepageBlock) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ product_ids: productIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            reorderStatusDiv.className = 'alert alert-success';
                            reorderStatusDiv.textContent = data.message;
                        } else {
                            reorderStatusDiv.className = 'alert alert-danger';
                            reorderStatusDiv.textContent = 'Bir hata oluştu.';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        reorderStatusDiv.className = 'alert alert-danger';
                        reorderStatusDiv.textContent = 'Sunucu hatası oluştu.';
                    });
                }
            });
        }
    });
    </script>
@endpush
