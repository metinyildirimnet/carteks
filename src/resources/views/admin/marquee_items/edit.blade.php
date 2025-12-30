@extends('admin.layouts.new_app')

@section('title', 'Kayan Yazı Öğesi Düzenle')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Kayan Yazı Öğesi Düzenle: {{ Str::limit($marqueeItem->content, 50) }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="marquee-item-form" action="{{ route('admin.marquee-items.update', $marqueeItem->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Laravel'de PUT/PATCH metotları için --}}
                <div class="card-body">
                    <div id="validation-errors" class="alert alert-danger" style="display: none;">
                        <ul></ul>
                    </div>

                    <div class="form-group">
                        <label for="content">İçerik</label>
                        <textarea id="content" name="content" class="form-control" rows="3" required>{{ old('content', $marqueeItem->content) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="is_active">Aktif mi?</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $marqueeItem->is_active) == 1 ? 'selected' : '' }}>Evet</option>
                            <option value="0" {{ old('is_active', $marqueeItem->is_active) == 0 ? 'selected' : '' }}>Hayır</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sort_order">Sıralama (İsteğe bağlı)</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', $marqueeItem->sort_order) }}">
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
        const form = document.getElementById('marquee-item-form');
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
                    window.location.href = "{{ route('admin.marquee-items.index') }}"; // Redirect to list
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
