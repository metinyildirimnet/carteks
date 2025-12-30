@extends('admin.layouts.new_app')

@section('title', 'Kategori Düzenle')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Kategori Düzenle: {{ $category->name }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="category-form" action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Laravel'de PUT/PATCH metotları için --}}
                <div class="card-body">
                    <div id="validation-errors" class="alert alert-danger" style="display: none;">
                        <ul></ul>
                    </div>

                    <div class="form-group">
                        <label for="name">Kategori Adı</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Açıklama (İsteğe bağlı)</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('category-form');
        const validationErrorsDiv = document.getElementById('validation-errors');
        const validationErrorsList = validationErrorsDiv.querySelector('ul');

        // AJAX Form Submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevent default form submission

            validationErrorsDiv.style.display = 'none';
            validationErrorsList.innerHTML = '';

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST', // Laravel handles PUT/PATCH via _method field
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Indicate AJAX request
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    // Success
                    window.location.href = result.redirect; // Redirect to category list
                } else if (response.status === 422) {
                    // Validation errors
                    for (const key in result.errors) {
                        result.errors[key].forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            validationErrorsList.appendChild(li);
                        });
                    }
                    validationErrorsDiv.style.display = 'block';
                } else {
                    // Other errors
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
