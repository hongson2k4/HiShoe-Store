@extends('admin.layout.main')
@section('content')
<form action="{{ route('products_variant.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label for="product_id">Chọn sản phẩm:</label>
        <select class="form-control" name="product_id" id="product_id" required>
            <option value="">-- Chọn sản phẩm --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
    </div>
    
    
    <button type="submit" class="btn btn-success">Thêm id</button>
</form>
@endsection