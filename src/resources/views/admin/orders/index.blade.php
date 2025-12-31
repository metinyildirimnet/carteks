@extends('admin.layouts.new_app')

@section('title', 'Siparişler')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sipariş Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders.trashed') }}" class="btn btn-warning btn-sm mr-2">
                            <i class="fas fa-trash-alt"></i> Silinmiş Siparişler
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm" id="bulk-actions-btn" disabled>Toplu İşlemler</button>
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" id="bulk-actions-dropdown" disabled>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="#" id="bulk-delete-action">Seçilenleri Sil</a>
                                <a class="dropdown-item" href="#" id="bulk-update-status-action">Durumlarını Güncelle</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px;"><input type="checkbox" id="check-all"></th>
                                <th style="width: 10px">ID</th>
                                <th>Sipariş Kodu</th>
                                <th>Kullanıcı</th>
                                <th>Toplam Tutar</th>
                                <th>Durum</th>
                                <th>Tarih</th>
                                <th style="width: 100px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr data-id="{{ $order->id }}">
                                    <td><input type="checkbox" class="row-checkbox" value="{{ $order->id }}"></td>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->user->name ?? 'Misafir' }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }} ₺</td>
                                    <td><span class="badge" style="background-color: {{ $order->status->color }}; color: #fff;">{{ $order->status->name }}</span></td>
                                    <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->order_code) }}" class="btn btn-sm btn-info">Detay</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Henüz sipariş bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Toplu Durum Güncelle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bulk_order_status_id">Yeni Durum</label>
                        <select class="form-control" id="bulk_order_status_id">
                            @foreach($orderStatuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary" id="save-status-update">Değişiklikleri Kaydet</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Common variables
    const checkAll = $('#check-all');
    const rowCheckboxes = $('.row-checkbox');
    const bulkActionsBtn = $('#bulk-actions-btn');
    const bulkActionsDropdown = $('#bulk-actions-dropdown');
    const csrfToken = '{{ csrf_token() }}'; // Store token

    // Function to control button state
    function updateBulkActionsState() {
        const anyChecked = rowCheckboxes.is(':checked');
        bulkActionsBtn.prop('disabled', !anyChecked);
        bulkActionsDropdown.prop('disabled', !anyChecked);
    }

    // Event handler for "select all"
    checkAll.on('change', function() {
        rowCheckboxes.prop('checked', this.checked);
        updateBulkActionsState();
    });

    // Event handler for individual checkboxes
    rowCheckboxes.on('change', function() {
        if (rowCheckboxes.filter(':checked').length === rowCheckboxes.length) {
            checkAll.prop('checked', true);
        } else {
            checkAll.prop('checked', false);
        }
        updateBulkActionsState();
    });

    // --- Bulk Action Logic ---

    // 1. Bulk Delete
    $('#bulk-delete-action').on('click', function(e) {
        e.preventDefault();
        const selectedIds = rowCheckboxes.filter(':checked').map(function() { return $(this).val(); }).get();

        if (selectedIds.length === 0) {
            toastr.warning('Lütfen en az bir sipariş seçin.');
            return;
        }

        if (confirm('Seçili ' + selectedIds.length + ' siparişi silmek istediğinizden emin misiniz?')) {
            $.ajax({
                url: '{{ route("admin.orders.bulk-delete") }}',
                type: 'POST',
                data: { ids: selectedIds },
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Bir hata oluştu.';
                    toastr.error(errorMessage);
                }
            });
        }
    });

    // 2. Bulk Status Update
    const updateStatusModal = $('#updateStatusModal');
    $('#bulk-update-status-action').on('click', function(e) {
        e.preventDefault();
        if (rowCheckboxes.filter(':checked').length === 0) {
            toastr.warning('Lütfen en az bir sipariş seçin.');
            return;
        }
        updateStatusModal.modal('show');
    });

    $('#save-status-update').on('click', function() {
        const selectedIds = rowCheckboxes.filter(':checked').map(function() { return $(this).val(); }).get();
        const newStatusId = $('#bulk_order_status_id').val();

        if (selectedIds.length === 0) {
            toastr.warning('Hata: Seçili sipariş bulunamadı.'); // This should ideally not happen
            return;
        }

        $.ajax({
            url: '{{ route("admin.orders.bulk-update-status") }}',
            type: 'POST',
            data: {
                ids: selectedIds,
                order_status_id: newStatusId
            },
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(response) {
                updateStatusModal.modal('hide');
                toastr.success(response.message);
                location.reload();
            },
            error: function(xhr) {
                updateStatusModal.modal('hide');
                const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Durumlar güncellenirken bir hata oluştu.';
                toastr.error(errorMessage);
            }
        });
    });

    // Initial state check on page load
    updateBulkActionsState();
});
</script>
@endpush
