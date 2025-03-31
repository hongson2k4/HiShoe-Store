@extends('client.layout.main')

@section('title', 'Shop')

@section('content')
    <style>
        .card {
            display: flex;
            flex-direction: column;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Lọc sản phẩm -->
            <div class="col-lg-3 col-md-4 mb-4">
                <h5 class="mb-3">Lọc sản phẩm</h5>
                <form method="GET" action="{{ route('shop') }}">
                    <div class="mb-4">
                        <label><strong>Khoảng giá</strong></label>
                        <input type="number" class="form-control" name="price_min" min="0" max="{{ $maxPrice }}" 
                               value="{{ request('price_min', 0) }}">
                        <input type="number" class="form-control mt-2" name="price_max" min="0" max="{{ $maxPrice }}" 
                               value="{{ request('price_max', $maxPrice) }}">
                        <small>{{ request('price_min', 0) }}đ - {{ request('price_max', $maxPrice) }}đ</small>
                    </div>
                    <div class="mb-4">
                        <label><strong>Danh mục</strong></label>
                        <ul class="list-unstyled">
                            @foreach($categories as $category)
                                <li>
                                    <input type="checkbox" name="category_id[]" value="{{ $category->id }}" 
                                           {{ in_array($category->id, request('category_id', [])) ? 'checked' : '' }}> 
                                    {{ $category->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mb-4">
                        <label><strong>Nhãn hàng</strong></label>
                        <ul class="list-unstyled">
                            @foreach($brands as $brand)
                                <li>
                                    <input type="checkbox" name="brand_id[]" value="{{ $brand->id }}" 
                                           {{ in_array($brand->id, request('brand_id', [])) ? 'checked' : '' }}> 
                                    {{ $brand->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mb-4">
    <label><strong>Kích thước</strong></label>
    <select class="form-control" name="size_id">
        <option value="">Tất cả</option>
        @foreach($sizes as $size)
            <option value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'selected' : '' }}>
                {{ $size->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label><strong>Màu sắc</strong></label>
    <select class="form-control" name="color_id">
        <option value="">Tất cả</option>
        @foreach($colors as $color)
            <option value="{{ $color->id }}" {{ request('color_id') == $color->id ? 'selected' : '' }}>
                {{ $color->name }}
            </option>
        @endforeach
    </select>
</div>
                    <button type="submit" class="btn btn-primary btn-block">Áp dụng</button>
                </form>
            </div>
            <!-- Nội dung danh sách sản phẩm -->
            <div class="col-lg-9 col-md-8">
                <h3 class="mb-3">Danh sách sản phẩm của cửa hàng</h3>
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100">
                                @if($product->is_best_seller)
                                    <span class="badge badge-danger position-absolute" style="top: 10px; left: 10px;">Bán chạy</span>
                                @endif
                                <img src="{{ $product->image_url ? Storage::url($product->image_url) : asset('images/default-product.jpg') }}" class="card-img-top" alt="{{ $product->name }}">
                                <div class="card-body text-center">
                                    <a class="card-title" href="{{ route('detail', $product->id) }}">{{ $product->name }}</a>
                                    <p class="text-muted">{{ Str::limit($product->description, 50) }}</p>
                                    <p class="text-danger font-weight-bold">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection