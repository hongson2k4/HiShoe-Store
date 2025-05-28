@extends('admin.layout.main')

@section('content')
    <div class="container-fluid mt-3">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h2 class="mb-0">Quản lý sản phẩm</h2>
                <div>
                    <a class="btn btn-success" href="{{ route('products.create') }}">Thêm mới sản phẩm</a>
                    <a class="btn btn-secondary" href="{{ route('products.hidden') }}">Hiển thị sản phẩm ẩn</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('products.list') }}" method="GET" class="form-inline mb-3 float-end">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm sản phẩm"
                        value="{{ request()->query('search') }}">
                    <button type="submit" class="btn btn-success me-2">Tìm kiếm</button>
                    <a href="{{ route('products.list') }}" class="btn btn-secondary">Đặt lại</a>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark">
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
                                        <span
                                            class="{{ $product->status == 0 ? 'badge bg-success text-white px-2 rounded' : 'badge bg-danger text-white px-2 rounded' }}">
                                            {{ $product->status == 0 ? 'Còn hàng' : 'Hết hàng' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-warning btn-sm me-1"
                                                href="{{ route('products.edit', $product->id) }}" title="Chỉnh sửa">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            {{-- Xóa nút xóa cứng, chỉ để nút ẩn --}}
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('products.variant.list', $product->id) }}" title="Biến thể">
                                                <i class="fas fa-tshirt"></i>
                                            </a>
                                            <form action="{{ route('products.hide', $product->id) }}" method="post"
                                                style="display:inline;">
                                                @csrf
                                                <button class="btn btn-danger btn-sm me-1"
                                                    onclick="return confirm('Xác nhận ẩn sản phẩm này?')" title="Ẩn">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
