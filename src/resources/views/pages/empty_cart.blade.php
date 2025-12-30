@extends('layouts.app')

@section('title', 'Sepetiniz Boş')

@section('content')
<div class="container" style="margin-top: 50px; margin-bottom: 50px; text-align: center;">
    <div class="card" style="max-width: 500px; margin: auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h1 style="font-size: 28px; margin-bottom: 20px;">Sepetiniz Boş!</h1>
        <p style="font-size: 16px; color: #777; margin-bottom: 30px;">Alışverişe devam etmek için ürünlerimize göz atın.</p>
        <a href="{{ route('home') }}" class="btn-primary" style="padding: 12px 25px; font-size: 16px; text-decoration: none;">Alışverişe Başla</a>
    </div>
</div>
@endsection
