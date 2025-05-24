@extends('client.layout.main')

@section('title', 'Nhãn hàng: ' . $brand->name)

@section('content')
    <style>
        /* Container styling */
        .brand-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 15px;
        }

        .brand-container h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .brand-container h2::after {
            content: '';
            width: 80px;
            height: 3px;
            background: #007bff;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Product card styling */
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .product-card a {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }

        .product-card .card-body {
            padding: 1rem;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-card h5 {
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-card p {
            font-size: 1.2rem;
            font-weight: 600;
            color: #dc3545;
            margin: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .brand-container h2 {
                font-size: 1.5rem;
            }

            .product-card img {
                height: 180px;
            }

            .product-card h5 {
                font-size: 1rem;
            }

            .product-card p {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .product-card {
                margin-bottom: 1.5rem;
            }
        }
    </style>

    <div class="brand-container">
        <h2>Nhãn hàng: {{ $brand->name }}</h2>
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <a href="{{ route('detail', ['product_id' => $product->id]) }}">
                            <img src="{{ $product->image_url ? Storage::url($product->image_url) : asset('images/default-product.jpg') }}"
                                 alt="{{ $product->name }}" class="img-fluid">
                            <div class="card-body">
                                <h5>{{ $product->name }}</h5>
                                <p>{{ number_format($product->price, 0, ',', '.') }} VND</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
