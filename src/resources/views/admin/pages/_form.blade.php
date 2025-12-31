@csrf
<div class="card-body">
    <div class="form-group">
        <label for="title">Sayfa Başlığı</label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title"
               value="{{ old('title', $page->title ?? '') }}" required>
        @error('title')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="content">İçerik</label>
        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="20">{{ old('content', $page->content ?? '') }}</textarea>
        @error('content')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <div class="custom-control custom-switch">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" class="custom-control-input" id="is_published" value="1"
                   @if(old('is_published', $page->is_published ?? true)) checked @endif>
            <label class="custom-control-label" for="is_published">Yayında</label>
        </div>
    </div>
</div>
<!-- /.card-body -->

<div class="card-footer">
    <button type="submit" class="btn btn-primary">Kaydet</button>
    <a href="{{ route('admin.design.pages.index') }}" class="btn btn-secondary">İptal</a>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/qfeo8r67o5837y4z22osywinhbhouzmx3gfs4bssioj0rkif/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#content',
        plugins: 'code table lists image link fullscreen preview',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | link image | fullscreen preview',
        height: 500,
        // Turkish language pack
        language: 'tr',
        language_url: 'https://cdn.tiny.cloud/1/qfeo8r67o5837y4z22osywinhbhouzmx3gfs4bssioj0rkif/tinymce/6/langs/tr.js'
    });
</script>
@endpush
