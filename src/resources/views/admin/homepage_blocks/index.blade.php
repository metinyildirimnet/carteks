@extends('admin.layouts.new_app')

@section('title', 'Ana Sayfa Blokları')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ana Sayfa Blokları Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.homepage-blocks.create') }}" class="btn btn-success btn-sm">Yeni Blok Ekle</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div id="reorder-status" class="alert" style="display: none; margin: 15px;"></div>
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 10px">Sıra</th>
                                    <th>Başlık</th>
                                    <th>Tip</th>
                                    <th>Görünürlük</th>
                                    <th>Aktif</th>
                                    <th style="width: 150px">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-blocks">
                                @forelse ($homepageBlocks as $block)
                                    <tr data-id="{{ $block->id }}">
                                        <td class="sort-order">{{ $block->sort_order }}</td>
                                        <td>{{ $block->title }}</td>
                                        <td>
                                            @if($block->type === 'product')
                                                <span class="badge badge-info">Ürün Bloğu</span>
                                            @else
                                                <span class="badge badge-secondary">Görsel Bloğu</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($block->show_on_desktop)
                                                <span class="badge badge-light">Masaüstü</span>
                                            @endif
                                            @if($block->show_on_mobile)
                                                <span class="badge badge-dark">Mobil</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($block->is_active)
                                                <span class="badge badge-success">Evet</span>
                                            @else
                                                <span class="badge badge-danger">Hayır</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.homepage-blocks.edit', $block->id) }}" class="btn btn-sm btn-primary">Düzenle</a>
                                            <form action="{{ route('admin.homepage-blocks.destroy', $block->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bu bloğu silmek istediğinizden emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Henüz ana sayfa bloğu bulunmuyor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('scripts')
<!-- SortableJS is already included in the main layout, so no need to add it again -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortableBlocks = document.getElementById('sortable-blocks');
        const reorderStatusDiv = document.getElementById('reorder-status');

        if (sortableBlocks) {
            new Sortable(sortableBlocks, {
                animation: 150,
                ghostClass: 'bg-light', // Class name for the drop placeholder
                onEnd: function (evt) {
                    reorderStatusDiv.style.display = 'block';
                    reorderStatusDiv.className = 'alert alert-info';
                    reorderStatusDiv.textContent = 'Sıralama güncelleniyor...';

                    const newOrder = [];
                    sortableBlocks.querySelectorAll('tr').forEach((row, index) => {
                        newOrder.push({
                            id: row.dataset.id,
                            sort_order: index + 1 // Use index as the new sort order
                        });
                    });

                    fetch('{{ route('admin.homepage-blocks.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ order: newOrder })
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Throw an error to be caught by the .catch block
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            reorderStatusDiv.className = 'alert alert-success';
                            reorderStatusDiv.textContent = data.message || 'Sıralama başarıyla güncellendi.';
                            
                            // Update the displayed sort_order numbers
                            sortableBlocks.querySelectorAll('tr').forEach((row, index) => {
                                const sortCell = row.querySelector('.sort-order');
                                if(sortCell) sortCell.textContent = index + 1;
                            });
                        } else {
                            // Handle cases where response.ok is true but data.success is false
                            reorderStatusDiv.className = 'alert alert-danger';
                            reorderStatusDiv.textContent = data.message || 'Sıralama güncellenemedi.';
                        }
                    })
                    .catch(error => {
                        console.error('AJAX hatası:', error);
                        reorderStatusDiv.className = 'alert alert-danger';
                        let errorMessage = 'Sunucu hatası oluştu. Lütfen tekrar deneyin.';
                        if (error.errors) {
                            errorMessage = Object.values(error.errors).flat().join(' ');
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        reorderStatusDiv.textContent = errorMessage;
                    });
                }
            });
        }
    });
</script>
@endpush
