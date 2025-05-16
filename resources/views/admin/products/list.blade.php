@extends('admin.layout.main')
@section('content')
    <a class="btn btn-success m-2" href="{{ route('products.create') }}">Thêm mới sản phẩm</a>
    <form action="{{ route('products.list') }}" method="GET" class="form-inline mb-3 float-right">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search products"
            value="{{ request()->query('search') }}">
        <button type="submit" class="btn btn-success">Search</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Loại giày</th>
                <th>Thương hiệu</th>
                <th>Hình ảnh chính</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $key => $product)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        @php
                            $categories = \App\Models\Product_categories::where('product_id', $product->id)
                                ->with('category')
                                ->get()
                                ->pluck('category.name')
                                ->toArray();
                        @endphp
                        {{ implode(', ', $categories) ?: 'N/A' }}
                    </td>
                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                    <td>
                        @if ($product->image_url)
                            <img src="{{ Storage::url($product->image_url) }}" width="100" alt="Main Image">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
    <span class="{{ $product->status == 0 ? 'badge badge-success text-white px-2 rounded' : 'badge badge-danger text-white px-2 rounded' }}">
        {{ $product->status == 0 ? 'Còn hàng' : 'Hết hàng' }}
    </span>
</td>
                    <td>
                        <a class="btn btn-warning" href="{{ route('products.edit', $product->id) }}"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="post" style="display:inline;">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger" onclick="return confirm('Xác nhận xóa ?')"><i class="fas fa-trash"></i></button>
                        </form>
                        <a class="btn btn-info" href="{{ route('products.variant.list', $product->id) }}"><i class="fas fa-tshirt"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection