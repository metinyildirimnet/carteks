<p class="text-muted">Müşterileriniz havale ile ödeme seçeneğini kullandığında görecekleri banka hesap bilgilerinizi buradan yönetebilirsiniz. Müşterinin sipariş sonrası göreceği ek açıklamaları da (örn. "Açıklamaya sipariş numaranızı yazınız") her bir hesap için belirleyebilirsiniz.</p>

<hr>

<h4>Banka Hesapları</h4>

<div id="accounts-container">
    @if(isset($module->settings['accounts']) && is_array($module->settings['accounts']))
        @foreach($module->settings['accounts'] as $index => $account)
            <div class="account-group card card-body mb-3">
                <button type="button" class="btn btn-danger btn-sm remove-account" style="position: absolute; top: 10px; right: 10px;">Kaldır</button>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Banka Adı</label>
                            <input type="text" name="settings[accounts][{{ $index }}][bank_name]" class="form-control" value="{{ $account['bank_name'] ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Hesap Sahibi Adı</label>
                            <input type="text" name="settings[accounts][{{ $index }}][account_holder]" class="form-control" value="{{ $account['account_holder'] ?? '' }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>IBAN</label>
                    <input type="text" name="settings[accounts][{{ $index }}][iban]" class="form-control" value="{{ $account['iban'] ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Açıklama (Örn: Sipariş Numarası)</label>
                    <input type="text" name="settings[accounts][{{ $index }}][description]" class="form-control" value="{{ $account['description'] ?? '' }}">
                </div>
            </div>
        @endforeach
    @endif
</div>

<button type="button" id="add-account" class="btn btn-success mt-2"><i class="fas fa-plus"></i> Yeni Hesap Ekle</button>

<div id="account-template" style="display: none;">
    <div class="account-group card card-body mb-3">
        <button type="button" class="btn btn-danger btn-sm remove-account" style="position: absolute; top: 10px; right: 10px;">Kaldır</button>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Banka Adı</label>
                    <input type="text" name="settings[accounts][__INDEX__][bank_name]" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Hesap Sahibi Adı</label>
                    <input type="text" name="settings[accounts][__INDEX__][account_holder]" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>IBAN</label>
            <input type="text" name="settings[accounts][__INDEX__][iban]" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Açıklama (Örn: Sipariş Numarası)</label>
            <input type="text" name="settings[accounts][__INDEX__][description]" class="form-control">
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let accountIndex = {{ (isset($module->settings['accounts']) && is_array($module->settings['accounts'])) ? count($module->settings['accounts']) : 0 }};

    $('#add-account').on('click', function() {
        let template = $('#account-template').html().replace(/__INDEX__/g, accountIndex);
        $('#accounts-container').append(template);
        accountIndex++;
    });

    $('#accounts-container').on('click', '.remove-account', function() {
        $(this).closest('.account-group').remove();
    });
});
</script>
@endpush
