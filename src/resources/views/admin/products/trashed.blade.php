@extends('admin.layouts.new_app')

@section('title', 'Silinmiş Ürünler')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Silinmiş Ürünler Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-default btn-sm">Ürün Listesine Geri Dön</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>Başlık</th>
                                <th>Kategori</th>
                                <th>Fiyat</th>
                                <th>İndirimli Fiyat</th>
                                <th>Silinme Tarihi</th>
                                <th style="width: 200px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->category->name ?? 'Yok' }}</td>
                                    <td>{{ number_format($product->price, 2) }} ₺</td>
                                    <td>{{ $product->discounted_price ? number_format($product->discounted_price, 2) . ' ₺' : '-' }}</td>
                                    <td>{{ $product->deleted_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Geri Yükle</button>
                                        </form>
                                        <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bu ürünü kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Kalıcı Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Silinmiş ürün bulunmuyor.</td>
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
