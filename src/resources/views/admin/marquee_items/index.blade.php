@extends('admin.layouts.new_app')

@section('title', 'Kayan Yazı Öğeleri')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kayan Yazı Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.marquee-items.create') }}" class="btn btn-success btn-sm">Yeni Kayan Yazı Ekle</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>İçerik</th>
                                <th>Aktif mi?</th>
                                <th>Sıralama</th>
                                <th style="width: 150px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($marqueeItems as $marqueeItem)
                                <tr>
                                    <td>{{ $marqueeItem->id }}</td>
                                    <td>{{ Str::limit($marqueeItem->content, 50) }}</td>
                                    <td>
                                        @if ($marqueeItem->is_active)
                                            <span class="badge badge-success">Evet</span>
                                        @else
                                            <span class="badge badge-danger">Hayır</span>
                                        @endif
                                    </td>
                                    <td>{{ $marqueeItem->sort_order ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.marquee-items.edit', $marqueeItem->id) }}" class="btn btn-sm btn-primary">Düzenle</a>
                                        <form action="{{ route('admin.marquee-items.destroy', $marqueeItem->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bu kayan yazı öğesini silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Henüz kayan yazı öğesi bulunmuyor.</td>
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
