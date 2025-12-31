@extends('admin.layouts.new_app')

@section('title', $module->name . ' Modülü Ayarları')

@section('content')
<form id="module-settings-form" action="{{ route('admin.modules.update', $module) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@yield('title')</h3>
        </div>
        <div class="card-body">
            {{-- Dynamically include the settings partial for the specific module --}}
            @includeFirst(['admin.modules.settings.' . $module->key, 'admin.modules.settings._default'])
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary">Geri</a>
            <button type="submit" id="module-save-btn" class="btn btn-primary">Ayarları Kaydet</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // This script ensures the form submits even if other scripts interfere.
    $(document).ready(function() {
        $('#module-save-btn').on('click', function(e) {
            e.preventDefault(); // Stop any other potential handlers
            $('#module-settings-form').submit(); // Explicitly submit the form
        });
    });
</script>
@endpush
