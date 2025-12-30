@csrf
<div class="card-body">
    {{-- Desktop Image --}}
    <div class="form-group">
        <label for="desktop_image">Masaüstü Görseli</label>
        <input type="file" name="desktop_image" class="form-control-file" id="desktop_image" accept="image/*">
        @if(isset($slide) && $slide->desktop_image_path)
            <img src="{{ $slide->desktop_image_url }}" alt="Masaüstü Görseli" class="img-thumbnail mt-2" width="200">
        @endif
        @error('desktop_image') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    {{-- Mobile Image --}}
    <div class="form-group">
        <label for="mobile_image">Mobil Görseli</label>
        <input type="file" name="mobile_image" class="form-control-file" id="mobile_image" accept="image/*">
        @if(isset($slide) && $slide->mobile_image_path)
            <img src="{{ $slide->mobile_image_url }}" alt="Mobil Görseli" class="img-thumbnail mt-2" width="100">
        @endif
        @error('mobile_image') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <hr>

    {{-- Content --}}
    <div class="form-group">
        <label for="content">Yazı Alanı (İsteğe Bağlı)</label>
        <textarea name="content" id="content" class="form-control" rows="4">{{ old('content', $slide->content ?? '') }}</textarea>
    </div>

    <hr>

    {{-- Button Text --}}
    <div class="form-group">
        <label for="button_text">Buton Yazısı (İsteğe Bağlı)</label>
        <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', $slide->button_text ?? '') }}">
    </div>

    {{-- Button Link --}}
    <div class="form-group">
        <label for="button_link_type">Buton Link Türü</label>
        <select name="button_link_type" id="button_link_type" class="form-control">
            <option value="">Link Yok</option>
            @foreach ($linkableTypes as $key => $value)
                <option value="{{ $key }}" 
                    @if(isset($slide) && $slide->getButtonLinkType() == $key) selected @endif>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Dynamic Button Link Fields --}}
    <div id="dynamic-link-fields">
        <div class="form-group" id="custom-link-field">
            <label for="button_url">URL</label>
            <input type="url" name="button_url" id="button_url" class="form-control" placeholder="https://example.com" value="{{ old('button_url', $slide->button_url ?? '') }}">
        </div>
        <div class="form-group" id="page-field">
            <label for="page_id">Sayfa Seçin</label>
            <select name="linkable_id_page" class="form-control">
                @foreach ($pages as $page)
                    <option value="{{ $page->id }}" 
                        @if(isset($slide) && $slide->linkable_type == \App\Models\Page::class && $slide->linkable_id == $page->id) selected @endif>
                        {{ $page->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="category-field">
            <label for="category_id">Kategori Seçin</label>
            <select name="linkable_id_category" class="form-control">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @if(isset($slide) && $slide->linkable_type == \App\Models\Category::class && $slide->linkable_id == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="product-field">
            <label for="product_id">Ürün Seçin</label>
            <select name="linkable_id_product" class="form-control">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        @if(isset($slide) && $slide->linkable_type == \App\Models\Product::class && !$slide->linkable->is_package && $slide->linkable_id == $product->id) selected @endif>
                        {{ $product->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="package-field">
            <label for="package_id">Paket Seçin</label>
            <select name="linkable_id_package" class="form-control">
                @foreach ($packages as $package)
                    <option value="{{ $package->id }}"
                        @if(isset($slide) && $slide->linkable_type == \App\Models\Product::class && $slide->linkable->is_package && $slide->linkable_id == $package->id) selected @endif>
                        {{ $package->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <input type="hidden" name="linkable_id" id="final_linkable_id">

    <hr>

    {{-- Is Active --}}
    <div class="form-group">
        <div class="custom-control custom-switch">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1"
                   @if(old('is_active', $slide->is_active ?? true)) checked @endif>
            <label class="custom-control-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">Kaydet</button>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">İptal</a>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = $('#button_link_type');
    const dynamicFields = $('#dynamic-link-fields > div');
    const finalLinkableId = $('#final_linkable_id');
    const form = finalLinkableId.closest('form');

    function toggleLinkFields() {
        const selectedType = typeSelect.val();
        dynamicFields.hide();
        $('#dynamic-link-fields select').prop('disabled', true);

        if (selectedType) {
            $(`#${selectedType}-field`).show().find('select, input').prop('disabled', false);
        }
    }

    typeSelect.on('change', toggleLinkFields);
    toggleLinkFields(); // Initial call

    form.on('submit', function() {
        const selectedType = typeSelect.val();
        let selectedId = null;
        if (selectedType && selectedType !== 'custom') {
            selectedId = $(`#${selectedType}-field select`).val();
        }
        finalLinkableId.val(selectedId);
    });
});
</script>
@endpush
