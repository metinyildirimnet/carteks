@extends('layouts.guest')

@section('title', 'E-posta Doğrulama')

@section('content')
    <h2>E-posta Doğrulama</h2>

    <div class="mb-4 text-sm text-gray-600">
        Kaydolduğunuz için teşekkürler! Devam etmeden önce, size e-postayla gönderdiğimiz bağlantıya tıklayarak e-posta adresinizi doğrulayabilir misiniz? Eğer e-postayı almadıysanız, size başka bir tane göndermekten memnuniyet duyarız.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Kayıt sırasında verdiğiniz e-posta adresine yeni bir doğrulama bağlantısı gönderildi.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <button type="submit" class="btn">
                    Doğrulama E-postasını Tekrar Gönder
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 text-blue-600 underline" style="background: none; border: none; cursor: pointer;">
                Çıkış Yap
            </button>
        </form>
    </div>
@endsection
