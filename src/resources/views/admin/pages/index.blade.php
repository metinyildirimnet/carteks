@extends('admin.layouts.new_app')

@section('title', 'Sayfalar')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tüm Sayfalar</h3>
            <div class="card-tools">
                <a href="{{ route('admin.design.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Sayfa Ekle
                </a>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Başlık</th>
                    <th>Sayfa Linki</th>
                    <th>Durum</th>
                    <th style="width: 150px">Eylemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->id }}</td>
                        <td>{{ $page->title }}</td>
                        <td><a href="{{ route('page.show', $page) }}" target="_blank">{{ route('page.show', $page) }}</a></td>
                        <td>
                            @if($page->is_published)
                                <span class="badge bg-success">Yayında</span>
                            @else
                                <span class="badge bg-secondary">Taslak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.design.pages.edit', $page) }}" class="btn btn-sm btn-warning">Düzenle</a>
                            <form action="{{ route('admin.design.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu sayfayı silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Henüz hiç sayfa oluşturulmamış.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $pages->links() }}
        </div>
    </div>
@endsection
