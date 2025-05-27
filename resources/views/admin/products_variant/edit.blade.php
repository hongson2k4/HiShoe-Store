@extends('admin.layout.main')
@section('content')
<div class="container">
    <h1>Update Product Variant</h1>
    <form action="{{ route('products.variant.update', ['product_id' => $product_id, 'id' => $product_variant->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="size_id">Kích thước</label>
            <select name="size_id" id="size_id" class="form-control">
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}" {{ $size->id == $product_variant->size_id ? 'selected' : '' }}>
                        {{ $size->name }}
                    </option>
                @endforeach
            </select>
            @error('size_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="color_id">Màu sắc</label>
            <select name="color_id" id="color_id" class="form-control">
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" {{ $color->id == $product_variant->color_id ? 'selected' : '' }}>
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
            @error('color_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="price">Giá</label>
            <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $product_variant->price) }}">
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
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
                <img src="{{ asset($product_variant->image_url) }}" alt="Current Image" class="image-preview__image" height="100">
                <span class="image-preview__default-text"></span>
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