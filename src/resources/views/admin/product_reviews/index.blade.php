@extends('admin.layouts.new_app')

@section('title', 'Ürün Değerlendirmeleri')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ürün Değerlendirmeleri Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.product-reviews.create') }}" class="btn btn-success btn-sm">Yeni Değerlendirme Ekle</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>Ürün</th>
                                <th>Kullanıcı</th>
                                <th>Puan</th>
                                <th>Yorum</th>
                                <th>Onaylı</th>
                                <th style="width: 150px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->product->title ?? 'Ürün Bulunamadı' }}</td>
                                    <td>{{ $review->user->name ?? 'Misafir' }}</td>
                                    <td>{{ $review->rating }} / 5</td>
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td>
                                        @if ($review->is_approved)
                                            <span class="badge badge-success">Evet</span>
                                        @else
                                            <span class="badge badge-danger">Hayır</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.product-reviews.edit', $review->id) }}" class="btn btn-sm btn-primary">Düzenle</a>
                                        <form action="{{ route('admin.product-reviews.destroy', $review->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bu değerlendirmeyi silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Henüz ürün değerlendirmesi bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $reviews->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
