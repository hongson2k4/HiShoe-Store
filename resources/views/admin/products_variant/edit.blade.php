@extends('admin.layout.main')
@section('content')
<div class="container">
    <h2>Cập nhật biến thể cho: {{ $products->name }}</h2>
    <form action="{{ route('products.variant.update', ['product_id' => $product_id, 'id' => $product_variant->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="price">Giá</label>
            <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $product_variant->price) }}">
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        @if($product_variant->color)
        <div class="mb-3">
            <label class="form-label">Color</label>
            <input class="form-control" type="text" value="{{ $product_variant->color->name }}" readonly>
        </div>
        @endif
        @if($product_variant->size)
        <div class="mb-3">
            <label class="form-label">Size</label>
            <input class="form-control" type="text" value="{{ $product_variant->size->name }}" readonly>
        </div>
        @endif
        <div class="form-group">
            <label for="stock_quantity">Số lượng</label>
            <input type="text" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product_variant->stock_quantity) }}">
            @error('stock_quantity')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="image_url">Ảnh biến thể</label>
            <input type="file" name="image_url" id="image_url" class="form-control-file">
            <div class="image-preview" id="imagePreview">
                @if($product_variant->image_url)
                    <img src="{{ asset('storage/' . $product_variant->image_url) }}" alt="Current Image" class="image-preview__image" height="100">
                @else
                    <span class="image-preview__default-text">Chưa có ảnh</span>
                @endif
            </div>
            @error('image_url')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
<script>
    const imageUpload = document.getElementById('image_url');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = imagePreview.querySelector('.image-preview__image');
    const previewDefaultText = imagePreview.querySelector('.image-preview__default-text');

    imageUpload.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            previewDefaultText.style.display = "none";
            previewImage.style.display = "block";

            reader.addEventListener('load', function() {
                previewImage.setAttribute('src', this.result);
            });

            reader.readAsDataURL(file);
        } else {
            previewDefaultText.style.display = null;
            previewImage.style.display = null;
            previewImage.setAttribute('src', '');
        }
    });
</script>

@endsection