@extends('client.layout.main')

@section('title', 'Shop')

@section('content')
    <style>
        /* General container styling */
        .shop-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 15px;
        }

        /* Sidebar styling */
        .sidebar {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .sidebar h5 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .sidebar .form-group {
            margin-bottom: 1.5rem;
        }

        .sidebar label {
            font-weight: 500;
            color: #444;
            margin-bottom: 0.5rem;
            display: block;
        }

        .sidebar input[type="number"],
        .sidebar select {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 0.5rem;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        .sidebar input[type="number"]:focus,
        .sidebar select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }

        .sidebar .checkbox-list {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .sidebar .checkbox-list li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .sidebar .checkbox-list input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .sidebar .btn-primary {
            width: 100%;
            padding: 0.75rem;
            border-radius: 6px;
            background: #007bff;
            border: none;
            transition: background 0.3s ease;
        }

        .sidebar .btn-primary:hover {
            background: #0056b3;
        }

        /* Product card styling */
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .product-card img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }

        .product-card .card-body {
            padding: 1rem;
            text-align: center;
            background: #fff;
        }

        .product-card .card-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
        }

        .product-card .card-title:hover {
            color: #007bff;
        }

        .product-card .description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
            height: 40px;
            overflow: hidden;
        }

        .product-card .price {
            font-size: 1.2rem;
            font-weight: 600;
            color: #dc3545;
        }

        .product-card .badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 500;
            z-index: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                margin-bottom: 2rem;
            }

            .product-card img {
                height: 180px;
            }

            .product-card .card-title {
                font-size: 1rem;
            }

            .product-card .price {
                font-size: 1.1rem;
            }
        }
    </style>

    <div class="shop-container">
        <div class="row">
            <!-- Sidebar Lọc sản phẩm -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="sidebar">
                    <h5>Lọc sản phẩm</h5>
                    <form method="GET" action="{{ route('shop') }}">
                        <div class="form-group">
                            <label>Khoảng giá</label>
                            <input type="number" class="form-control" name="price_min" min="0" max="{{ $maxPrice }}"
                                   value="{{ request('price_min', 0) }}" placeholder="Giá tối thiểu">
                            <input type="number" class="form-control mt-2" name="price_max" min="0" max="{{ $maxPrice }}"
                                   value="{{ request('price_max', $maxPrice) }}" placeholder="Giá tối đa">
                            <small class="text-muted">{{ number_format(request('price_min', 0), 0, ',', '.') }}đ - {{ number_format(request('price_max', $maxPrice), 0, ',', '.') }}đ</small>
                        </div>
                        <div class="form-group">
                            <label>Danh mục</label>
                            <ul class="list-unstyled checkbox-list">
                                @foreach($categories as $category)
                                    <li>
                                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                                               {{ in_array($category->id, request('category_id', [])) ? 'checked' : '' }}>
                                        {{ $category->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>Nhãn hàng</label>
                            <ul class="list-unstyled checkbox-list">
                                @foreach($brands as $brand)
                                    <li>
                                        <input type="checkbox" name="brand_id[]" value="{{ $brand->id }}"
                                               {{ in_array($brand->id, request('brand_id', [])) ? 'checked' : '' }}>
                                        {{ $brand->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>Kích thước</label>
                            <select class="form-control" name="size_id">
                                <option value="">Tất cả</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Màu sắc</label>
                            <select class="form-control" name="color_id">
                                <option value="">Tất cả</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ request('color_id') == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Áp dụng</button>
                    </form>
                </div>
            </div>
            <!-- Nội dung danh sách sản phẩm -->
            <div class="col-lg-9 col-md-8">
                <h3 class="mb-4">Danh sách sản phẩm của cửa hàng</h3>
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="product-card h-100">
                                @if($product->is_best_seller)
                                    <span class="badge badge-danger">Bán chạy</span>
                                @endif
                                <img src="{{ $product->image_url ? Storage::url($product->image_url) : asset('images/default-product.jpg') }}"
                                     class="card-img-top" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <a class="card-title" href="{{ route('detail', $product->id) }}">{{ $product->name }}</a>
                                    <p class="description">{{ Str::limit($product->description, 50) }}</p>
                                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
