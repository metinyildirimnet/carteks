@extends('layouts.guest')

@section('title', 'Şifreyi Onayla')

@section('content')
    <h2>Şifreyi Onayla</h2>

    <div class="mb-4 text-sm text-gray-600">
        Bu, uygulamanın güvenli bir alanıdır. Devam etmeden önce lütfen şifrenizi onaylayın.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="form-group">
            <label for="password">Şifre</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control">
            @error('password')
                <div class="alert alert-danger mt-3 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="btn">
                Onayla
            </button>
        </div>
    </form>
@endsection
