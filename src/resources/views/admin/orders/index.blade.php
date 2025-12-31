@extends('admin.layouts.new_app')

@section('title', 'Siparişler')

@section('content')
    <!-- Filter Section -->
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Filtrele</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="filter-form" class="form-horizontal">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="order_code" class="form-control" placeholder="Sipariş Kodu">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="customer_name" class="form-control" placeholder="Müşteri Adı">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="customer_phone" class="form-control" placeholder="Müşteri Telefon">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="product_id" class="form-control">
                                <option value="">Tüm Ürünler</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                             <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                  </span>
                                </div>
                                <input type="text" name="start_date" class="form-control" placeholder="Başlangıç Tarihi" onfocus="(this.type='date')">
                              </div>
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="form-group">
                             <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                  </span>
                                </div>
                                <input type="text" name="end_date" class="form-control" placeholder="Bitiş Tarihi" onfocus="(this.type='date')">
                              </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Filtrele</button>
                        <button type="button" id="clear-filters-btn" class="btn btn-secondary">Temizle</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                                <th>Sipariş Kodu</th>
                                <th>Müşteri</th>
                                <th>Ürünler</th>
                                <th>Toplam Tutar</th>
                                <th>Durum</th>
                                <th>Tarih</th>
                                <th style="width: 100px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body">
                            @forelse ($orders as $order)
                                @include('admin.orders._order_row', ['order' => $order])
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
    // =================================================
    // Common Variables & Functions
    // =================================================
    const checkAll = $('#check-all');
    const tableBody = $('#orders-table-body');
    const bulkActionsBtn = $('#bulk-actions-btn');
    const bulkActionsDropdown = $('#bulk-actions-dropdown');
    const csrfToken = '{{ csrf_token() }}';

    function updateBulkActionsState() {
        const anyChecked = tableBody.find('.row-checkbox:checked').length > 0;
        bulkActionsBtn.prop('disabled', !anyChecked);
        bulkActionsDropdown.prop('disabled', !anyChecked);
    }

    // =================================================
    // Event Handlers
    // =================================================

    // --- Checkbox Logic (with event delegation) ---
    tableBody.on('change', '.row-checkbox', function() {
        const totalCheckboxes = tableBody.find('.row-checkbox').length;
        const checkedCheckboxes = tableBody.find('.row-checkbox:checked').length;
        checkAll.prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
        updateBulkActionsState();
    });

    checkAll.on('change', function() {
        tableBody.find('.row-checkbox').prop('checked', this.checked);
        updateBulkActionsState();
    });

    // --- Bulk Action Logic ---
    // 1. Bulk Delete
    $('#bulk-delete-action').on('click', function(e) {
        e.preventDefault();
        const selectedIds = tableBody.find('.row-checkbox:checked').map(function() { return $(this).val(); }).get();

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
                    $('#filter-form').trigger('submit'); // Refilter/reload table data
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
        if (tableBody.find('.row-checkbox:checked').length === 0) {
            toastr.warning('Lütfen en az bir sipariş seçin.');
            return;
        }
        updateStatusModal.modal('show');
    });

    $('#save-status-update').on('click', function() {
        const selectedIds = tableBody.find('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
        const newStatusId = $('#bulk_order_status_id').val();

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
                 if (response.newStatus) {
                    selectedIds.forEach(function(id) {
                        const row = $('tr[data-id="' + id + '"]');
                        const statusBadge = row.find('td:nth-child(6) .badge');
                        if (statusBadge.length) {
                            statusBadge.css('background-color', response.newStatus.color).text(response.newStatus.name);
                        }
                    });
                }
                tableBody.find('.row-checkbox').prop('checked', false);
                checkAll.prop('checked', false);
                updateBulkActionsState();
            },
            error: function(xhr) {
                updateStatusModal.modal('hide');
                const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Durumlar güncellenirken bir hata oluştu.';
                toastr.error(errorMessage);
            }
        });
    });

    // --- AJAX Filtering Logic ---
    const filterForm = $('#filter-form');
    const clearFiltersBtn = $('#clear-filters-btn');

    filterForm.on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: '{{ route("admin.orders.index") }}',
            type: 'GET',
            data: formData,
            beforeSend: function() {
                tableBody.html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</td></tr>');
            },
            success: function(response) {
                tableBody.html(response.html);
                checkAll.prop('checked', false);
                updateBulkActionsState();
            },
            error: function() {
                tableBody.html('<tr><td colspan="8" class="text-center">Filtreleme sırasında bir hata oluştu.</td></tr>');
            }
        });
    });

    clearFiltersBtn.on('click', function() {
        filterForm[0].reset();
        filterForm.trigger('submit');
    });

    // Initial state check
    updateBulkActionsState();
});
</script>
@endpush
