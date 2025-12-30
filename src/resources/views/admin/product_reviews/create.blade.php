@extends('admin.layouts.new_app')

@section('title', 'Yeni Değerlendirme Ekle')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Yeni Değerlendirme Ekle</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.product-reviews.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="product_id">Ürün</label>
                        <select id="product_id" name="product_id" class="form-control">
                            <option value="">Ürün Seçiniz</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="user_id">Kullanıcı (İsteğe Bağlı)</label>
                        <select id="user_id" name="user_id" class="form-control">
                            <option value="">Kullanıcı Seçiniz</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="rating">Puan</label>
                        <select id="rating" name="rating" class="form-control">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('rating')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="comment">Yorum (İsteğe Bağlı)</label>
                        <textarea id="comment" name="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                        @error('comment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_approved" name="is_approved" value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_approved">Onaylı</label>
                        </div>
                        @error('is_approved')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Değerlendirme Ekle</button>
                    <a href="{{ route('admin.product-reviews.index') }}" class="btn btn-secondary">Geri Dön</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
