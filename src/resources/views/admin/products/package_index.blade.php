@extends('admin.layouts.new_app')

@section('title', 'Paketler')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Paket Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">Yeni Paket Ekle</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>Görsel</th>
                                <th>Başlık</th>
                                <th>Kategori</th>
                                <th>Fiyat</th>
                                <th>İndirimli Fiyat</th>
                                <th>Değerlendirmeler</th>
                                <th style="width: 150px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if ($product->images->first())
                                            <img src="{{ $product->images->first()->image_path }}" alt="{{ $product->title }}" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/50x50.png?text=No+Image" alt="No Image" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->category->name ?? 'Yok' }}</td>
                                    <td>{{ number_format($product->price, 2) }} ₺</td>
                                    <td>{{ $product->discounted_price ? number_format($product->discounted_price, 2) . ' ₺' : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.product-reviews.index', ['product_id' => $product->id]) }}" class="btn btn-sm btn-info">
                                            <div style="display: flex; align-items: center; gap: 5px;">
                                                <div class="stars" style="color: #ffc107;">
                                                    @php
                                                        $averageRating = $product->average_rating;
                                                    @endphp
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
                                                ({{ $product->total_reviews }})
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product->slug) }}" class="btn btn-sm btn-primary">Düzenle</a>
                                        <form action="{{ route('admin.products.destroy', $product->slug) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bu paketi silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Henüz paket bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
