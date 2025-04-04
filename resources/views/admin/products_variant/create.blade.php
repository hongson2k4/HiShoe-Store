@extends('admin.layout.main')
@section('content')
<div class="container">
    <h1>Create Product Variant</h1>
    <form action="{{ route('products.variant.store', $product_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="size_id">Size</label>
            <select name="size_id" id="size_id" class="form-control">
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="color_id">Color</label>
            <select name="color_id" id="color_id" class="form-control">
                @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" id="price" class="form-control">
        </div>
        <div class="form-group">
            <label for="stock_quantity">Stock Quantity</label>
            <input type="text" name="stock_quantity" id="stock_quantity" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection