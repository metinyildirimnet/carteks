@extends('admin.layouts.new_app')

@section('title', 'Menü Yönetimi')

@section('content')
    <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="menu-tabs" role="tablist">
                @foreach ($menuGroups as $index => $group)
                    <li class="nav-item">
                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $group->key }}" data-toggle="pill"
                            href="#content-{{ $group->key }}" role="tab" aria-controls="content-{{ $group->key }}"
                            aria-selected="{{ $index == 0 ? 'true' : 'false' }}">{{ $group->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="menu-tabs-content">
                @foreach ($menuGroups as $index => $group)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $group->key }}"
                        role="tabpanel" aria-labelledby="tab-{{ $group->key }}">

                        {{-- Group Title Edit Form --}}
                        <form action="{{ route('admin.design.menus.group.update', $group) }}" method="POST" class="form-inline mb-4">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="title-{{ $group->key }}" class="mr-2">Grup Başlığı:</label>
                                <input type="text" name="title" id="title-{{ $group->key }}" class="form-control mr-2"
                                    value="{{ $group->title }}" required>
                                <button type="submit" class="btn btn-success">Başlığı Güncelle</button>
                            </div>
                        </form>

                        <hr>

                        {{-- Menu Items List --}}
                        <h5 class="mb-2">Menü Linkleri</h5>
                        <ul class="list-group sortable-menu" id="menu-group-{{ $group->id }}" data-group-id="{{ $group->id }}">
                            @forelse ($group->items as $item)
                                @php
                                    $item_type = 'custom';
                                    if ($item->linkable_type) {
                                        switch ($item->linkable_type) {
                                            case \App\Models\Page::class: $item_type = 'page'; break;
                                            case \App\Models\Category::class: $item_type = 'category'; break;
                                            case \App\Models\Product::class:
                                                $item_type = $item->linkable->is_package ? 'package' : 'product';
                                                break;
                                        }
                                    }
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                    data-id="{{ $item->id }}"
                                    data-title="{{ $item->title }}"
                                    data-type="{{ $item_type }}"
                                    data-url="{{ $item->url }}"
                                    data-linkable-id="{{ $item->linkable_id }}"
                                    data-target="{{ $item->target }}"
                                    >
                                    <span>
                                        <i class="fas fa-arrows-alt handle mr-2" style="cursor: move;"></i>
                                        <strong>{{ $item->title }}</strong>
                                        <small class="text-muted ml-2">({{ $item->getUrl() }})</small>
                                    </span>
                                    <div>
                                        <button class="btn btn-xs btn-warning edit-item-btn">Düzenle</button>
                                        <form action="{{ route('admin.design.menus.item.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu linki silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">Sil</button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center">Bu gruba henüz link eklenmemiş.</li>
                            @endforelse
                        </ul>
                        <button class="btn btn-primary mt-3 add-item-btn" data-group-id="{{ $group->id }}">
                            <i class="fas fa-plus"></i> Yeni Link Ekle
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add/Edit Item Modal -->
    <div class="modal fade" id="item-modal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Yeni Link Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="item-form" action="" method="POST">
                    @csrf
                    <div id="form-method"></div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="item_title">Link Başlığı</label>
                            <input type="text" name="title" id="item_title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="item_type_select">Link Türü</label>
                            <select name="type" id="item_type_select" class="form-control">
                                @foreach ($linkableTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dynamic Fields based on Type --}}
                        <div id="dynamic-fields">
                            <div class="form-group" id="custom-link-field">
                                <label for="item_url">URL</label>
                                <input type="url" name="url" id="item_url" class="form-control" placeholder="https://example.com">
                            </div>
                            <div class="form-group" id="page-field" style="display: none;">
                                <label for="page_id">Sayfa Seçin</label>
                                <select name="linkable_id_page" class="form-control">
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->id }}">{{ $page->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="category-field" style="display: none;">
                                <label for="category_id">Kategori Seçin</label>
                                <select name="linkable_id_category" class="form-control">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="product-field" style="display: none;">
                                <label for="product_id">Ürün Seçin</label>
                                <select name="linkable_id_product" class="form-control">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="package-field" style="display: none;">
                                <label for="package_id">Paket Seçin</label>
                                <select name="linkable_id_package" class="form-control">
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="linkable_id" id="final_linkable_id">

                        <div class="form-group">
                            <label for="item_target">Link Hedefi</label>
                            <select name="target" id="item_target" class="form-control">
                                <option value="_self">Aynı Sekmede Aç</option>
                                <option value="_blank">Yeni Sekmede Aç</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SortableJS for drag-and-drop --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Sortable
            const sortableLists = document.querySelectorAll('.sortable-menu');
            sortableLists.forEach(list => {
                new Sortable(list, {
                    handle: '.handle',
                    animation: 150,
                    onEnd: function(evt) {
                        const order = Array.from(evt.target.children).map(el => el.dataset.id);
                        
                        fetch('{{ route('admin.design.menus.reorder') }}', {
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
            });

            // Modal Logic
            const modal = $('#item-modal');
            const form = $('#item-form');
            const modalTitle = $('#itemModalLabel');
            const formMethod = $('#form-method');
            const typeSelect = $('#item_type_select');
            const dynamicFields = $('#dynamic-fields > div');
            const finalLinkableId = $('#final_linkable_id');

            // Handle link type change
            typeSelect.on('change', function() {
                const selectedType = $(this).val();
                dynamicFields.hide();
                $('#dynamic-fields select').prop('disabled', true);

                switch (selectedType) {
                    case 'custom':
                        $('#custom-link-field').show();
                        break;
                    case 'page':
                        $('#page-field').show().find('select').prop('disabled', false);
                        break;
                    case 'category':
                        $('#category-field').show().find('select').prop('disabled', false);
                        break;
                    case 'product':
                        $('#product-field').show().find('select').prop('disabled', false);
                        break;
                    case 'package':
                        $('#package-field').show().find('select').prop('disabled', false);
                        break;
                }
            });

            // Open modal for ADDING a new item
            $('.add-item-btn').on('click', function() {
                const groupId = $(this).data('group-id');
                const action = '{{ url('admin/design/menus/groups') }}/' + groupId + '/items';
                
                form.attr('action', action);
                form.trigger('reset'); // Clear form fields
                formMethod.html(''); // No method spoofing for POST
                modalTitle.text('Yeni Link Ekle');
                typeSelect.prop('disabled', false);
                typeSelect.trigger('change');
                modal.modal('show');
            });

            // Open modal for EDITING an existing item
            $('.edit-item-btn').on('click', function() {
                const listItem = $(this).closest('li');
                const itemId = listItem.data('id');
                const action = '{{ url('admin/design/menus/items') }}/' + itemId;

                form.attr('action', action);
                form.trigger('reset');
                formMethod.html('@method('PUT')'); // Spoof PUT method for update
                modalTitle.text('Linki Düzenle');

                // Populate form
                $('#item_title').val(listItem.data('title'));
                $('#item_target').val(listItem.data('target'));
                typeSelect.val(listItem.data('type')).trigger('change'); // Set type and trigger change to show correct fields

                // Set the correct value in the correct select/input
                const type = listItem.data('type');
                if (type === 'custom') {
                    $('#item_url').val(listItem.data('url'));
                } else {
                    $(`#${type}-field select`).val(listItem.data('linkable-id'));
                }

                modal.modal('show');
            });

            // Before submitting, consolidate the linkable_id
            form.on('submit', function() {
                const selectedType = typeSelect.val();
                let selectedId = null;
                if (selectedType !== 'custom') {
                    selectedId = $(`#${selectedType}-field select`).val();
                }
                finalLinkableId.val(selectedId);
            });
        });
    </script>
@endpush
