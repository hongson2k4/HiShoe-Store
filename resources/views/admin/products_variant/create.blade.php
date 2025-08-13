@extends('admin.layout.main')
@section('content')
<div class="container">
    <h2>Tạo biến thể cho: {{ $product->name }}</h2>
    <form action="{{ route('products.variant.store', $product_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="size_id">Kích thước</label>
            <select name="size_id" id="size_id" class="form-control">
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="color_id">Màu sắc</label>
            <select name="color_id" id="color_id" class="form-control">
                @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">Giá tiền</label>
            <input type="text" name="price" id="price" class="form-control">
        </div>
        <div class="form-group">
            <label for="stock_quantity">Số lượng</label>
            <input type="text" name="stock_quantity" id="stock_quantity" class="form-control">
        </div>
        <div class="form-group">
            <label for="image">Ảnh biến thể</label>
            <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Tạo mới</button>
    </form>
</div>
@endsection