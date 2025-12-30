@extends('layouts.guest')

@section('title', 'Kaydol')

@section('content')
    <h2>Kaydol</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name">Ad Soyad</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control">
            @error('name')
                <div class="alert alert-danger mt-3 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group mt-3">
            <label for="email">E-posta</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control">
            @error('email')
                <div class="alert alert-danger mt-3 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group mt-3">
            <label for="password">Şifre</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control">
            @error('password')
                <div class="alert alert-danger mt-3 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group mt-3">
            <label for="password_confirmation">Şifreyi Onayla</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control">
            @error('password_confirmation')
                <div class="alert alert-danger mt-3 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 text-blue-600" href="{{ route('login') }}">
                Zaten kayıtlı mısın?
            </a>

            <button type="submit" class="btn mt-3">
                Kaydol
            </button>
        </div>
    </form>
@endsection
