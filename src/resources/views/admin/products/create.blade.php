@extends('admin.layouts.new_app')

@section('title', 'Yeni Ürün Ekle')

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Yeni Ürün Bilgileri</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="product-form" action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div id="validation-errors" class="alert alert-danger" style="display: none;">
                        <ul></ul>
                    </div>

                    <div class="form-group">
                        <label for="title">Başlık</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <textarea id="description" name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Fiyat</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="discounted_price">İndirimli Fiyat (İsteğe bağlı)</label>
                        <input type="number" id="discounted_price" name="discounted_price" class="form-control" step="0.01" value="{{ old('discounted_price') }}">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">Kategori Seçiniz</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_package" name="is_package">
                            <label class="custom-control-label" for="is_package">Bu bir paket üründür</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Kullanım Alanları</label>
                        <div class="row">
                            @foreach($usageAreas as $area)
                                <div class="col-md-3 col-sm-6">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="usage_area_{{ Str::slug($area) }}" name="usage_areas[]" value="{{ $area }}" {{ in_array($area, old('usage_areas', [])) ? 'checked' : '' }}>
                                        <label for="usage_area_{{ Str::slug($area) }}" class="custom-control-label">{{ $area }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="package-products-section" style="display: none;">
                        <div class="form-group">
                            <label>Pakete Ürün Ekle</label>
                            <div class="input-group">
                                <select id="product-selector" class="form-control select2" style="width: 85%;">
                                    <option value="">Ürün seçin...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" id="add-product-to-package" class="btn btn-success">Ekle</button>
                                </div>
                            </div>
                        </div>
                        <table id="package-items-table" class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>Ürün Adı</th>
                                    <th style="width: 100px;">Adet</th>
                                    <th style="width: 50px;">Sil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Selected products will be appended here -->
                            </tbody>
                        </table>
                    </div>


                    <div class="form-group">
                        <label for="images">Ürün Görselleri</label>
                        <input type="file" id="images" name="images[]" class="form-control" multiple accept="image/*">
                        <div id="image-previews" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;"></div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('admin-assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    const form = document.getElementById('product-form');
    const imagesInput = document.getElementById('images');
    const imagePreviews = document.getElementById('image-previews');
    const validationErrorsDiv = document.getElementById('validation-errors');
    const validationErrorsList = validationErrorsDiv.querySelector('ul');

    const isPackageCheckbox = document.getElementById('is_package');
    const packageProductsSection = document.getElementById('package-products-section');
    const productSelector = document.getElementById('product-selector');
    const addProductBtn = document.getElementById('add-product-to-package');
    const packageItemsTbody = document.querySelector('#package-items-table tbody');

    // Toggle package section
    isPackageCheckbox.addEventListener('change', function() {
        packageProductsSection.style.display = this.checked ? 'block' : 'none';
    });

    // Add product to package table
    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelector.options[productSelector.selectedIndex];
        if (!selectedOption.value) return;

        const productId = selectedOption.value;
        const productTitle = selectedOption.text;

        const newRow = `
            <tr data-id="${productId}">
                <td>
                    ${productTitle}
                    <input type="hidden" name="contained_products[][id]" value="${productId}">
                </td>
                <td>
                    <input type="number" name="contained_products[][quantity]" class="form-control" value="1" min="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-package-item">Sil</button>
                </td>
            </tr>
        `;
        packageItemsTbody.insertAdjacentHTML('beforeend', newRow);

        // Disable selected option
        selectedOption.disabled = true;
        $(productSelector).val(null).trigger('change');
    });

    // Remove product from package table
    packageItemsTbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-package-item')) {
            const row = e.target.closest('tr');
            const productId = row.dataset.id;
            
            // Re-enable option in selector
            const optionToEnable = productSelector.querySelector(`option[value="${productId}"]`);
            if (optionToEnable) {
                optionToEnable.disabled = false;
            }
            
            row.remove();
        }
    });

    // Image Preview
    imagesInput.addEventListener('change', function() {
        imagePreviews.innerHTML = '';
        if (this.files) {
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    imagePreviews.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    });

    // AJAX Form Submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        validationErrorsDiv.style.display = 'none';
        validationErrorsList.innerHTML = '';

        const formData = new FormData(form);
        
        // Delete the original, non-indexed fields from formData, as they are not correctly formatted for the backend
        formData.delete('contained_products[][id]');
        formData.delete('contained_products[][quantity]');

        // Manually structure and append contained_products data in a format the backend expects
        const rows = packageItemsTbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            const idInput = row.querySelector('input[type="hidden"]');
            const quantityInput = row.querySelector('input[type="number"]');
            
            if (idInput && quantityInput) {
                formData.append(`contained_products[${index}][id]`, idInput.value);
                formData.append(`contained_products[${index}][quantity]`, quantityInput.value);
            }
        });

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (response.ok) {
                window.location.href = result.redirect;
            } else if (response.status === 422) {
                for (const key in result.errors) {
                    result.errors[key].forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        validationErrorsList.appendChild(li);
                    });
                }
                validationErrorsDiv.style.display = 'block';
            } else {
                const li = document.createElement('li');
                li.textContent = result.message || 'Bir hata oluştu.';
                validationErrorsList.appendChild(li);
                validationErrorsDiv.style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            const li = document.createElement('li');
            li.textContent = 'Sunucuya bağlanırken bir hata oluştu.';
            validationErrorsList.appendChild(li);
            validationErrorsDiv.style.display = 'block';
        }
    });
});
</script>
@endpush
@endsection