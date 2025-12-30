@extends('admin.layouts.new_app')

@section('title', 'Sipariş Detayı #' . $order->id)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Sipariş Detayı #{{ $order->id }}</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ route('admin.orders.update', $order->order_code) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Müşteri Bilgileri</h4>
                                <div class="form-group">
                                    <label for="customer_name">Ad Soyad</label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ $order->customer_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="customer_phone">Telefon</label>
                                    <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="{{ $order->customer_phone }}">
                                </div>
                                <div class="form-group">
                                    <label for="customer_address">Adres</label>
                                    <textarea name="customer_address" id="customer_address" class="form-control">{{ $order->customer_address }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="customer_city">İl</label>
                                    <input type="text" name="customer_city" id="customer_city" class="form-control" value="{{ $order->customer_city }}">
                                </div>
                                <div class="form-group">
                                    <label for="customer_district">İlçe</label>
                                    <input type="text" name="customer_district" id="customer_district" class="form-control" value="{{ $order->customer_district }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Sipariş Bilgileri</h4>
                                <p><strong>Sipariş Kodu:</strong> {{ $order->order_code }}</p>
                                <p><strong>Kullanıcı:</strong> {{ $order->user->name ?? 'Misafir' }}</p>
                                <p><strong>Toplam Tutar:</strong> {{ number_format($order->total_amount, 2) }} ₺</p>
                                @if ($order->discount_amount > 0)
                                    <p><strong>Uygulanan İndirim:</strong> -{{ number_format($order->discount_amount, 2) }} ₺</p>
                                @endif
                                @if ($order->discounts->isNotEmpty())
                                    <h5>Uygulanan İndirimler:</h5>
                                    <ul>
                                        @foreach ($order->discounts as $discount)
                                            <li>{{ $discount->description }} (-{{ number_format($discount->amount, 2) }} ₺)</li>
                                        @endforeach
                                    </ul>
                                @endif
                                <p><strong>Sipariş Tarihi:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                                <div class="form-group">
                                    <label for="payment_method">Ödeme Yöntemi</label>
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="cash_on_delivery" {{ $order->payment_method == 'cash_on_delivery' ? 'selected' : '' }}>Kapıda Ödeme (Nakit)</option>
                                        <option value="card_on_delivery" {{ $order->payment_method == 'card_on_delivery' ? 'selected' : '' }}>Kapıda Ödeme (Kredi Kartı)</option>
                                        <option value="bank_transfer" {{ $order->payment_method == 'bank_transfer' ? 'selected' : '' }}>Havale / EFT</option>
                                        <option value="credit_card" {{ $order->payment_method == 'credit_card' ? 'selected' : '' }}>Online Kredi Kartı</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="order_status_id">Durum</label>
                                    <select name="order_status_id" id="order_status_id" class="form-control">
                                        @foreach ($orderStatuses as $status)
                                            <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-4">Sipariş Edilen Ürünler</h4>
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Ürün Adı</th>
                                    <th>Miktar</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->title ?? 'Ürün Bulunamadı' }}</td>
                                        <td><input type="number" name="items[{{ $item->id }}][quantity]" class="form-control" value="{{ $item->quantity }}" min="1"></td>
                                        <td>{{ number_format($item->price, 2) }} ₺</td>
                                        <td>{{ number_format($item->quantity * $item->price, 2) }} ₺</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-item" data-item-id="{{ $item->id }}">Kaldır</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Geri Dön</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderForm = document.querySelector('form');
    const itemsTableBody = document.querySelector('.table tbody');

    function checkItemCount() {
        const visibleRows = itemsTableBody.querySelectorAll('tr:not([style*="display: none"])');
        const removeButtons = itemsTableBody.querySelectorAll('.remove-item');

        if (visibleRows.length <= 1) {
            visibleRows.forEach(row => {
                const button = row.querySelector('.remove-item');
                if (button) {
                    button.disabled = true;
                }
            });
        } else {
            // This part is tricky because a disabled button might be re-enabled.
            // It's safer to iterate through all visible rows and enable their buttons.
            visibleRows.forEach(row => {
                const button = row.querySelector('.remove-item');
                if(button) {
                    button.disabled = false;
                }
            });
        }
    }

    itemsTableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            const button = e.target;
            const itemId = button.dataset.itemId;
            const row = button.closest('tr');

            // Create a hidden input to mark for removal
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_items[]';
            hiddenInput.value = itemId;
            orderForm.appendChild(hiddenInput);

            // Hide the row
            row.style.display = 'none';

            // Disable inputs in the hidden row so they are not submitted
            row.querySelectorAll('input').forEach(input => {
                input.disabled = true;
            });

            // Re-check button states
            checkItemCount();
        }
    });

    // Initial check on page load
    checkItemCount();
});
</script>
@endpush
