@extends('admin.layouts.new_app')

@section('title', 'Slider Yönetimi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tüm Slaytlar</h3>
            <div class="card-tools">
                <a href="{{ route('admin.design.sliders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Slayt Ekle
                </a>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group" id="sortable-slides">
                @forelse($slides as $slide)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $slide->id }}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-arrows-alt handle mr-3" style="cursor: move;"></i>
                            <img src="{{ $slide->mobile_image_url }}" alt="" class="img-thumbnail" width="100" style="margin-right: 15px;">
                            <img src="{{ $slide->desktop_image_url }}" alt="" class="img-thumbnail d-none d-md-block" width="200">
                            <span class="ml-3">
                                @if($slide->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('admin.design.sliders.edit', $slide) }}" class="btn btn-sm btn-warning">Düzenle</a>
                            <form action="{{ route('admin.design.sliders.destroy', $slide) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu slaytı silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center">Henüz hiç slayt oluşturulmamış.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortableList = document.getElementById('sortable-slides');
            if (sortableList) {
                new Sortable(sortableList, {
                    handle: '.handle',
                    animation: 150,
                    onEnd: function(evt) {
                        const order = Array.from(evt.target.children).map(el => el.dataset.id);
                        
                        fetch('{{ route('admin.design.sliders.reorder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order: order })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.status === 'success') {
                                toastr.success(data.message);
                            } else {
                                toastr.error('Sıralama güncellenirken bir hata oluştu.');
                            }
                        });
                    }
                });
            }
        });
    </script>
@endpush
