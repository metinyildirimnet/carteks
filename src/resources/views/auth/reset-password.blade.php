@extends('layouts.guest')

@section('title', 'Şifreyi Sıfırla')

@section('content')
    <h2>Şifreyi Sıfırla</h2>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">E-posta</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus class="form-control">
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
            <button type="submit" class="btn">
                Şifreyi Sıfırla
            </button>
        </div>
    </form>
@endsection
