@extends('layouts.guest')

@section('title', 'Şifremi Unuttum')

@section('content')
    <h2>Şifremi Unuttum</h2>

    <div class="mb-4 text-sm text-gray-600">
        Şifrenizi mi unuttunuz? Sorun değil. Sadece e-posta adresinizi bize bildirin, size yeni bir tane seçmenize izin verecek bir şifre sıfırlama bağlantısı e-postayla gönderelim.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">E-posta</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
            @error('email')
                <div class="alert alert-danger mt-3 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn">
                Şifre Sıfırlama Bağlantısı Gönder
            </button>
        </div>
    </form>
@endsection
