@extends('admin.layout.main')

@section('content')
<div class="container-fluid mt-3">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
            <h2 class="mb-0">Sản phẩm đã ẩn</h2>
            <a class="btn btn-primary" href="{{ route('products.list') }}">Quay lại danh sách</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Loại giày</th>
                            <th>Thương hiệu</th>
                            <th>Hình ảnh</th>
                            <th>Khôi phục</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($product->image_url)
                                        <img src="{{ Storage::url($product->image_url) }}" width="100" alt="Main Image">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('products.restore', $product->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success btn-sm"
                                            onclick="return confirm('Khôi phục sản phẩm này?')" title="Khôi phục">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
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
