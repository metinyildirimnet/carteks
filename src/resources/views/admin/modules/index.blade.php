@extends('admin.layouts.new_app')

@section('title', 'Modül Yönetimi')

@section('styles')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #28a745;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Modül Listesi</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Modül Adı</th>
                        <th>Açıklama</th>
                        <th style="width: 100px">Durum</th>
                        <th style="width: 120px">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                        <tr>
                            <td>{{ $module->id }}</td>
                            <td>{{ $module->name }}</td>
                            <td>{{ $module->description }}</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="status-toggle" data-id="{{ $module->id }}" {{ $module->is_active ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td>
                                <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-cog"></i> Ayarlar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.status-toggle').on('change', function() {
        const moduleId = $(this).data('id');
        const isActive = $(this).is(':checked');
        
        $.ajax({
            url: '{{ url("admin/modules") }}/' + moduleId,
            type: 'POST', // Should be PUT/PATCH, but POST is simpler with HTML forms
            data: {
                _method: 'PUT',
                is_active: isActive ? 1 : 0 // Send 1 or 0 instead of true/false
            },
            // headers are set globally in new_app.blade.php
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error('Durum güncellenirken bir hata oluştu.');
                }
            },
            error: function(xhr) {
                toastr.error('Sunucu hatası: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Bilinmeyen bir hata oluştu.'));
                // Revert the switch on error
                $(this).prop('checked', !isActive);
            }
        });
    });
});
</script>
@endpush
