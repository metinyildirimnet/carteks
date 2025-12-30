@extends('admin.layouts.new_app')

@section('title', 'Değerlendirme Düzenle')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Değerlendirme Düzenle</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.product-reviews.update', $productReview->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="product_title">Ürün</label>
                        <input type="text" id="product_title" class="form-control" value="{{ $productReview->product->title ?? 'Ürün Bulunamadı' }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="user_name">Kullanıcı</label>
                        <input type="text" id="user_name" class="form-control" value="{{ $productReview->user->name ?? 'Misafir' }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="rating">Puan</label>
                        <select id="rating" name="rating" class="form-control">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $productReview->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('rating')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="comment">Yorum</label>
                        <textarea id="comment" name="comment" class="form-control" rows="3">{{ old('comment', $productReview->comment) }}</textarea>
                        @error('comment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_approved" name="is_approved" value="1" {{ old('is_approved', $productReview->is_approved) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_approved">Onaylı</label>
                        </div>
                        @error('is_approved')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                    <a href="{{ route('admin.product-reviews.index') }}" class="btn btn-secondary">Geri Dön</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
