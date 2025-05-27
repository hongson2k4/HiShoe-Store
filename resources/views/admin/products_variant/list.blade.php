@extends('admin.layout.main')
@section('content')
    <h1>Danh sách biến thể sản phẩm</h1>
    <h3>Tên sản phẩm: {{ $product->name }}</h3>
    <a class="btn btn-success m-2" href="{{ route('products.variant.create', ['product_id' => $product_id]) }}">Thêm mới biến thể</a>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh biến thể</th>
                <th>Kích thước</th>
                <th>Màu sắc</th>
                <th>Mã màu</th>
                <th>Số lượng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products_variant as $key => $variant)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        @if ($variant->image_url)
                            <img src="{{ asset('storage/' . $variant->image_url) }}" alt="Variant Image" style="width: 100px; height: 100px;">
                        @else
                            <span>Chưa có ảnh</span>
                        @endif
                    <td>{{ $variant->size->name }}</td>
                    <td>{{ $variant->color->name }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="me-2 m-2">{{ $variant->color->code }}</span>
                            <div style="width: 30px; height: 30px; background-color: {{ $variant->color->code }}; border: 1px solid #ccc;"></div>
                        </div>
                    </td>
                    <td>{{ $variant->stock_quantity }}</td>
                    <td>
                        <a class="btn btn-warning m-2" href="{{ route('products.variant.edit', ['product_id' => $variant->product_id, 'id' => $variant->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('products.variant.destroy', ['product_id' => $variant->product_id, 'id' => $variant->id]) }}" method="post" style="display:inline;">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger m-2" onclick="return confirm('Xác nhận xóa ?')"> <i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection