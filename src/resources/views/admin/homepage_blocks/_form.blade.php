<div class="card-body">
    {{-- Common Fields --}}
    <div class="form-group">
        <label for="title">Başlık</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $homepageBlock->title ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="description">Açıklama (İsteğe bağlı)</label>
        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $homepageBlock->description ?? '') }}</textarea>
    </div>

    {{-- Block Type Selector --}}
    <div class="form-group">
        <label>Blok Tipi</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="type" id="type_product" value="product" {{ old('type', $homepageBlock->type ?? 'product') == 'product' ? 'checked' : '' }}>
            <label class="form-check-label" for="type_product">Ürün Bloğu</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="type" id="type_visual" value="visual" {{ old('type', $homepageBlock->type ?? '') == 'visual' ? 'checked' : '' }}>
            <label class="form-check-label" for="type_visual">Görsel Bloğu</label>
        </div>
    </div>

    <hr>

    {{-- Product Block Fields --}}
    <div id="product-block-fields" style="display: none;">
        <div class="form-group">
            <label for="products">Ürünler</label>
            <select id="products" name="products[]" class="form-control select2" multiple="multiple" data-placeholder="Ürün Seçiniz" style="width: 100%;">
                @foreach($allProducts as $product)
                    <option value="{{ $product->id }}" 
                        @if(isset($homepageBlock) && $homepageBlock->products->contains($product->id)) selected @endif>
                        {{ $product->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Visual Block Fields --}}
    <div id="visual-block-fields" style="display: none;">
        <div class="form-group">
            <label for="image">Görsel</label>
            <input type="file" name="image" class="form-control-file" id="image" accept="image/*">
            @if(isset($homepageBlock) && $homepageBlock->image_path)
                <img src="{{ $homepageBlock->image_url }}" alt="Görsel" class="img-thumbnail mt-2" width="200">
            @endif
            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="link_type">Görsel Link Türü</label>
            <select name="link_type" id="link_type" class="form-control">
                <option value="">Link Yok</option>
                @foreach ($linkableTypes as $key => $value)
                    <option value="{{ $key }}" 
                        @if(isset($homepageBlock) && $homepageBlock->getLinkType() == $key) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="dynamic-link-fields">
            <div class="form-group" id="custom-link-field-visual">
                <label for="link_url">URL</label>
                <input type="url" name="link_url" class="form-control" placeholder="https://example.com" value="{{ old('link_url', $homepageBlock->link_url ?? '') }}">
            </div>
            <div class="form-group" id="page-field-visual">
                <label>Sayfa Seçin</label>
                <select name="linkable_id_page" class="form-control">
                    @foreach ($pages as $page)
                        <option value="{{ $page->id }}" @if(isset($homepageBlock) && $homepageBlock->linkable_type == \App\Models\Page::class && $homepageBlock->linkable_id == $page->id) selected @endif>{{ $page->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" id="category-field-visual">
                <label>Kategori Seçin</label>
                <select name="linkable_id_category" class="form-control">
                     @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if(isset($homepageBlock) && $homepageBlock->linkable_type == \App\Models\Category::class && $homepageBlock->linkable_id == $category->id) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" id="product-field-visual">
                <label>Ürün Seçin</label>
                <select name="linkable_id_product" class="form-control">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @if(isset($homepageBlock) && $homepageBlock->linkable_type == \App\Models\Product::class && !$homepageBlock->linkable->is_package && $homepageBlock->linkable_id == $product->id) selected @endif>{{ $product->title }}</option>
                    @endforeach
                </select>
            </div>
             <div class="form-group" id="package-field-visual">
                <label>Paket Seçin</label>
                <select name="linkable_id_package" class="form-control">
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}" @if(isset($homepageBlock) && $homepageBlock->linkable_type == \App\Models\Product::class && $homepageBlock->linkable->is_package && $homepageBlock->linkable_id == $package->id) selected @endif>{{ $package->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="hidden" name="linkable_id" id="final_linkable_id">
    </div>

    <hr>

    {{-- Visibility and Status --}}
    <div class="form-group">
        <label>Görünürlük ve Durum</label>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $homepageBlock->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif (Ana sayfada gösterilsin)</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="show_on_desktop" name="show_on_desktop" value="1" {{ old('show_on_desktop', $homepageBlock->show_on_desktop ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="show_on_desktop">Masaüstünde Göster</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="show_on_mobile" name="show_on_mobile" value="1" {{ old('show_on_mobile', $homepageBlock->show_on_mobile ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="show_on_mobile">Mobilde Göster</label>
        </div>
    </div>
</div>
<!-- /.card-body -->

<div class="card-footer">
    <button type="submit" class="btn btn-primary">Kaydet</button>
    <a href="{{ route('admin.homepage-blocks.index') }}" class="btn btn-secondary">İptal</a>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin-assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2({ theme: 'bootstrap4' });

            function toggleBlockTypeFields() {
                if ($('#type_product').is(':checked')) {
                    $('#product-block-fields').show();
                    $('#visual-block-fields').hide();
                } else {
                    $('#product-block-fields').hide();
                    $('#visual-block-fields').show();
                }
            }

            function toggleLinkFields() {
                const selectedType = $('#link_type').val();
                $('#dynamic-link-fields > div').hide();
                $('#dynamic-link-fields select').prop('disabled', true);

                if (selectedType) {
                    $(`#${selectedType}-field-visual`).show().find('select, input').prop('disabled', false);
                }
            }

            $('input[name="type"]').on('change', toggleBlockTypeFields);
            $('#link_type').on('change', toggleLinkFields);
            
            toggleBlockTypeFields();
            toggleLinkFields();

            $('form').on('submit', function() {
                const linkType = $('#link_type').val();
                let linkableId = null;
                if (linkType && linkType !== 'custom') {
                    linkableId = $(`#${linkType}-field-visual select`).val();
                }
                $('#final_linkable_id').val(linkableId);

                // Handle checkbox values
                if (!$('#is_active').is(':checked')) $(this).append('<input type="hidden" name="is_active" value="0" />');
                if (!$('#show_on_desktop').is(':checked')) $(this).append('<input type="hidden" name="show_on_desktop" value="0" />');
                if (!$('#show_on_mobile').is(':checked')) $(this).append('<input type="hidden" name="show_on_mobile" value="0" />');
            });
        });
    </script>
@endpush
