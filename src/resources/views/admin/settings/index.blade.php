@extends('admin.layouts.new_app')

@section('title', 'Site Ayarları')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Site Ayarları</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-general-tab" data-toggle="pill"
                                    href="#custom-tabs-one-general" role="tab" aria-controls="custom-tabs-one-general"
                                    aria-selected="true">Genel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-whatsapp-tab" data-toggle="pill"
                                    href="#custom-tabs-one-whatsapp" role="tab" aria-controls="custom-tabs-one-whatsapp"
                                    aria-selected="false">WhatsApp</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-contact-tab" data-toggle="pill"
                                    href="#custom-tabs-one-contact" role="tab" aria-controls="custom-tabs-one-contact"
                                    aria-selected="false">İletişim</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-sms-tab" data-toggle="pill"
                                    href="#custom-tabs-one-sms" role="tab" aria-controls="custom-tabs-one-sms"
                                    aria-selected="false">SMS Ayarları</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-social-tab" data-toggle="pill"
                                    href="#custom-tabs-one-social" role="tab" aria-controls="custom-tabs-one-social"
                                    aria-selected="false">Sosyal Medya</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-pixel-tab" data-toggle="pill"
                                    href="#custom-tabs-one-pixel" role="tab" aria-controls="custom-tabs-one-pixel"
                                    aria-selected="false">Piksel Ayarları</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one-general" role="tabpanel"
                                aria-labelledby="custom-tabs-one-general-tab">
                                <div class="form-group">
                                    <label for="site_title">Site Başlığı</label>
                                    <input type="text" name="site_title" id="site_title" class="form-control"
                                        value="{{ $settings['site_title']->value ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="site_logo">Site Logosu</label>
                                    <input type="file" name="site_logo" id="site_logo" class="form-control">
                                    @if (isset($settings['site_logo']))
                                        <img src="{{ $settings['site_logo']->value }}" alt="Site Logo"
                                            style="max-width: 200px; margin-top: 10px;">
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-whatsapp" role="tabpanel"
                                aria-labelledby="custom-tabs-one-whatsapp-tab">
                                <div class="form-group">
                                    <label for="whatsapp_active">WhatsApp Aktif mi?</label>
                                    <select name="whatsapp_active" id="whatsapp_active" class="form-control">
                                        <option value="1"
                                            {{ (isset($settings['whatsapp_active']) && $settings['whatsapp_active']->value == 1) ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="0"
                                            {{ (isset($settings['whatsapp_active']) && $settings['whatsapp_active']->value == 0) ? 'selected' : '' }}>
                                            Pasif</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="whatsapp_number">WhatsApp Numarası</label>
                                    <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control"
                                        value="{{ $settings['whatsapp_number']->value ?? '' }}">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-contact" role="tabpanel"
                                aria-labelledby="custom-tabs-one-contact-tab">
                                <div class="form-group">
                                    <label for="contact_phone">Telefon</label>
                                    <input type="text" name="contact_phone" id="contact_phone" class="form-control"
                                        value="{{ $settings['contact_phone']->value ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="contact_address">Adres</label>
                                    <textarea name="contact_address" id="contact_address" class="form-control">{{ $settings['contact_address']->value ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-sms" role="tabpanel"
                                aria-labelledby="custom-tabs-one-sms-tab">
                                <div class="form-group">
                                    <label for="order_completion_sms_template">Sipariş Tamamlama SMS Şablonu</label>
                                    <textarea name="order_completion_sms_template" id="order_completion_sms_template" class="form-control"
                                        rows="5">{{ $settings['order_completion_sms_template']->value ?? '' }}</textarea>
                                    <small class="form-text text-muted">Kullanilabilecek degiskenler:
                                        customer_name,order_code,total_amount</small>
                                </div>
                                <div class="form-group">
                                    <label for="incomplete_order_sms_template">Tamamlanmamış Sipariş SMS Şablonu</label>
                                    <textarea name="incomplete_order_sms_template" id="incomplete_order_sms_template" class="form-control"
                                        rows="5">{{ $settings['incomplete_order_sms_template']->value ?? '' }}</textarea>
                                    <small class="form-text text-muted">Kullanilabilecek degiskenler:
                                        customer_name,order_code,total_amount</small>
                                </div>
                                <div class="form-group">
                                    <label for="sms_api_url">SMS API URL</label>
                                    <input type="url" name="sms_api_url" id="sms_api_url" class="form-control"
                                        value="{{ $settings['sms_api_url']->value ?? 'https://metinyildirim.net/api/smsapi.php' }}">
                                </div>
                                <div class="form-group">
                                    <label for="sms_sender_number">SMS Gönderen Numara</label>
                                    <input type="text" name="sms_sender_number" id="sms_sender_number"
                                        class="form-control" value="{{ $settings['sms_sender_number']->value ?? '' }}">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-social" role="tabpanel"
                                aria-labelledby="custom-tabs-one-social-tab">
                                <div class="form-group">
                                    <label for="social_facebook_url">Facebook URL</label>
                                    <input type="url" name="social_facebook_url" id="social_facebook_url" class="form-control" value="{{ $settings['social_facebook_url']->value ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="social_twitter_url">Twitter URL</label>
                                    <input type="url" name="social_twitter_url" id="social_twitter_url" class="form-control" value="{{ $settings['social_twitter_url']->value ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="social_instagram_url">Instagram URL</label>
                                    <input type="url" name="social_instagram_url" id="social_instagram_url" class="form-control" value="{{ $settings['social_instagram_url']->value ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="social_linkedin_url">LinkedIn URL</label>
                                    <input type="url" name="social_linkedin_url" id="social_linkedin_url" class="form-control" value="{{ $settings['social_linkedin_url']->value ?? '' }}">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-pixel" role="tabpanel"
                                aria-labelledby="custom-tabs-one-pixel-tab">
                                <div class="form-group">
                                    <label for="facebook_pixel_id">Facebook Piksel ID</label>
                                    <input type="text" name="facebook_pixel_id" id="facebook_pixel_id" class="form-control"
                                        value="{{ $settings['facebook_pixel_id']->value ?? '' }}">
                                    <small class="form-text text-muted">Facebook Piksel ID'nizi buraya girin (örn: 1234567890).</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection