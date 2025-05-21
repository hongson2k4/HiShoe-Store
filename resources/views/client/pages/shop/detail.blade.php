@extends('client.layout.main')

@section('title', 'Chi tiết sản phẩm')

@section('content')

    <style>
        .product-image {
            width: 100%;
            border-radius: 10px;
        }
        .variant-button {
            margin: 5px;
            padding: 10px 20px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        .variant-button.active {
            background-color: #007bff;
            color: white;
        }
        .variant-button:disabled {
            background-color: #e0e0e0;
            color: #a0a0a0;
            cursor: not-allowed;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .quantity-selector button {
            padding: 5px 10px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            cursor: pointer;
        }
        .quantity-selector input {
            width: 50px;
            text-align: center;
            border: 1px solid #ccc;
            margin: 0 5px;
        }
        .related-products {
            margin-top: 50px;
        }

        .carousel-container {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            flex: 0 0 25%; /* Hiển thị 4 sản phẩm */
            text-align: center;
            padding: 10px;
            box-sizing: border-box;
        }

        .carousel-item img {
            width: 100%;
            border-radius: 10px;
        }

        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 10;
        }

        .carousel-control.prev {
            left: 10px;
        }

        .carousel-control.next {
            right: 10px;
        }
    </style>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-6 col-md-12 text-center">
                <img src="{{ Storage::url($products->image_url) }}" class="product-image img-fluid"
                    alt="{{ $products->name }}">
            </div>
            <div class="col-lg-6 col-md-12">
                <h2 class="text-primary">{{ $products->name }}</h2>
                <p class="text-muted">Mã sản phẩm: {{ $products->id }}</p>
                <p>Giá sản phẩm: <h5 class="text-danger">{{ number_format($products->price, 0, ',', '.') }} VNĐ</h5></p>
                <p>Thương hiệu: {{ $products->brand->name }}</p>
                <p>Danh mục: {{ $products->category->name }}</p>

                <div class="variant-selector">
                    <div id="sizeButtons">
                        <p>Chọn kích cỡ:</p>
                        @foreach($availableSizes as $id => $name)
                            <button class="variant-button" data-size-id="{{ $id }}">{{ $name }}</button>
                        @endforeach
                    </div>

                    <div id="colorButtons">
                        <p>Chọn màu sắc:</p>
                        @foreach($availableColors as $id => $name)
                            <button class="variant-button" data-color-id="{{ $id }}">{{ $name }}</button>
                        @endforeach
                    </div>
                </div>

                <p id="stockInfo" class="text-muted">Chọn kích cỡ và màu sắc để xem số lượng hàng trong kho</p>

                <div class="quantity-selector">
                    <button id="decreaseQuantity">-</button>
                    <input type="number" id="quantityInput" value="1" min="1">
                    <button id="increaseQuantity">+</button>
                </div>

                <h4 class="text-danger mt-3" id="totalPrice">{{ number_format($products->price, 0, ',', '.') }} VNĐ</h4>

                <button id="addToCartButton" class="btn btn-success mt-3">Thêm vào giỏ hàng</button>
            </div>
        </div>
    </div>
    <div class="product-description mt-5 px-3">
    <div class="container">
        <h3 class="text-center mb-4">{{ $products->productDetails->first()->detail_title }}</h3>
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <p class="text-justify">{{ $products->productDetails->first()->detail_content }}</p>
            </div>
        </div>
        @if ($products->productDetails->first()->detail_image)
            <div class="row justify-content-center mt-4">
                <div class="col-12 col-md-10 text-center">
                    <img src="{{ Storage::url($products->productDetails->first()->detail_image) }}" alt="Description Image" class="img-fluid rounded shadow-sm">
                </div>
            </div>
        @endif
    </div>
</div>

    <div class="card bg-white p-3 mb-4 mt-4 ">
        <h4 class="fw-semibold">Bình luận</h4>
        @foreach ($comments as $cmt)
        <div class="d-flex align-items-start">
            <!-- Kiểm tra nếu user có avatar, nếu không thì sử dụng ảnh mặc định -->
            <img alt="Avatar of {{ $cmt->user->avatar }}" class="rounded-circle me-3" width="50" height="50" src="{{ $cmt->user->avatar ?? 'https://cdn.kona-blue.com/upload/kona-blue_com/post/images/2024/09/19/465/avatar-trang-1.jpg' }}">
    
            <div class="flex-grow-1">
                <div class="bg-light p-3 rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0 fw-semibold">
                            {{ $cmt->user->full_name }}
                        </h5>
                        <small class="text-muted">Lúc {{ $cmt->created_at->format('d/m/Y H:i') }}</small> <!-- Hiển thị thời gian bình luận -->
                    </div>
                    <p class="mb-0 text-dark">
                        {{ $cmt->content }} <!-- Nội dung bình luận -->
                    </p>
                </div>
                <a href="#" class="text-primary text-decoration-none small mt-2 d-inline-block">
                    <i class="fas fa-reply me-1"></i>
                    Trả lời
                </a>
            </div>
        </div>
    @endforeach
    
        <div class="mt-4">
          <form action="{{URL('comment/send')}}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $products->id }}">
            <input type="hidden" name="user_id" value="{{ Auth::check() ? Auth::guard('web')->user()->id : '' }}">
            <textarea class="form-control mb-3" rows="4" name="content" placeholder="Nhập nội dung bình luận..."></textarea>
            <button class="btn btn-success w-100 py-2 fw-semibold">
                GỬI BÌNH LUẬN
            </button>
          </form>
        </div>
    </div>

    <div class="related-products mt-5">
        <h3 class="text-center">Sản phẩm gợi ý</h3>
        <div class="carousel-container">
            <button class="carousel-control prev">&lt;</button>
            <div class="carousel">
                @foreach($products as $product)
                    <div class="carousel-item">
                        <a href="{{ route('detail', $products->id) }}">
                            <img src="{{ Storage::url($products->image_url) }}" alt="{{ $products->name }}" class="img-fluid">
                            <h5 class="text-center mt-2">{{ $products->name }}</h5>
                            <p class="text-center text-danger">{{ number_format($products->price, 0, ',', '.') }} VNĐ</p>
                        </a>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control next">&gt;</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sizeButtons = document.querySelectorAll('#sizeButtons .variant-button');
            const colorButtons = document.querySelectorAll('#colorButtons .variant-button');
            const quantityInput = document.getElementById('quantityInput');
            const decreaseQuantityButton = document.getElementById('decreaseQuantity');
            const increaseQuantityButton = document.getElementById('increaseQuantity');
            const addToCartButton = document.getElementById('addToCartButton');
            const stockInfo = document.getElementById('stockInfo');
            const totalPrice = document.getElementById('totalPrice');
            let selectedSize = null;
            let selectedColor = null;
            let quantity = 1;



            // Define the variants variable
            const variants = @json($variants);

            function updateStockInfo() {
                const selectedSizeId = selectedSize;
                const selectedColorId = selectedColor;

                let stockInfoText = 'Không có sẵn';
                let maxStock = 0;

                if (selectedSizeId && selectedColorId) {
                    const matchingVariant = variants.find(variant =>
                        variant.size_id == selectedSizeId && variant.color_id == selectedColorId
                    );

                    if (matchingVariant) {
                        maxStock = matchingVariant.stock_quantity;
                        totalPrice.textContent = matchingVariant.price;
                        stockInfoText = `Còn ${maxStock} sản phẩm`;
                    }
                }

                stockInfo.textContent = stockInfoText;
                quantityInput.max = maxStock; // Set the max attribute for the input
                return maxStock;
            }

            function validateQuantity() {
                const maxStock = updateStockInfo();
                if (quantity > maxStock) {
                    alert(`Số lượng bạn chọn vượt quá số lượng hàng trong kho (${maxStock} sản phẩm).`);
                    quantity = maxStock;
                    quantityInput.value = quantity;
                }
            }

            sizeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    sizeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    selectedSize = this.getAttribute('data-size-id');
                    updateStockInfo();
                });
            });

            colorButtons.forEach(button => {
                button.addEventListener('click', function() {
                    colorButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    selectedColor = this.getAttribute('data-color-id');
                    updateStockInfo();
                });
            });

            decreaseQuantityButton.addEventListener('click', function() {
                if (quantity > 1) {
                    quantity--;
                    quantityInput.value = quantity;
                }
            });

            increaseQuantityButton.addEventListener('click', function() {
                quantity++;
                validateQuantity();
            });

            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value >= 1) {
                    quantity = value;
                    validateQuantity();
                } else {
                    this.value = quantity;
                }
            });

            addToCartButton.addEventListener('click', function() {
                const maxStock = updateStockInfo();
                if (!selectedSize || !selectedColor) {
                    alert('Vui lòng chọn kích cỡ và màu sắc trước khi thêm vào giỏ hàng.');
                    return;
                }

                if (quantity > maxStock) {
                    alert(`Số lượng bạn chọn vượt quá số lượng hàng trong kho (${maxStock} sản phẩm).`);
                    return;
                }

                const url = '/cart/add';
                const payload = {
                    product_id: {{ $products->id }},
                    size_id: selectedSize,
                    color_id: selectedColor,
                    quantity: quantity
                };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Sản phẩm đã được thêm vào giỏ hàng.');
                    } else {
                        alert(data.message || 'Đã xảy ra lỗi khi thêm vào giỏ hàng.');
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    alert('Đã xảy ra lỗi khi thêm vào giỏ hàng.');
                });
            });
        });
    </script>
@endsection
