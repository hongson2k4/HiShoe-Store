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
                <p>Giá sản phẩm: <h4 class="text-danger" id="dynamicPrice">{{ number_format($products->price, 0, ',', '.') }} VNĐ</h4></p>
                <p>{{ $products->description }}</p>

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

                <div class="quantity-selector">
                    <button id="decreaseQuantity">-</button>
                    <input type="number" id="quantityInput" value="1" min="1">
                    <button id="increaseQuantity">+</button>
                </div>

                <h4 class="text-danger mt-3" id="totalPrice">{{ number_format($products->price, 0, ',', '.') }} VNĐ</h4>

                <button class="btn btn-success mt-3">Thêm vào giỏ hàng</button>
            </div>
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
            const priceDisplay = document.getElementById('dynamicPrice');
            const totalPriceDisplay = document.getElementById('totalPrice');
            const quantityInput = document.getElementById('quantityInput');
            const decreaseQuantityButton = document.getElementById('decreaseQuantity');
            const increaseQuantityButton = document.getElementById('increaseQuantity');
            let selectedSize = null;
            let selectedColor = null;
            let basePrice = {{ $products->price }};
            let quantity = 1;

            // Define the variants variable
            const variants = @json($variants);

            // Ensure product_id is dynamically set
            const productId = {{ $products->id }};

            function updatePrice() {
                if (selectedSize && selectedColor) {
                    const url = `/api/get-variant-price?product_id=${productId}&size_id=${selectedSize}&color_id=${selectedColor}`;
                    console.log('Fetching URL:', url); // Debugging log
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.price) {
                                basePrice = data.price;
                                priceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(basePrice) + 'đ';
                                updateTotalPrice();
                            } else {
                                priceDisplay.textContent = 'Không có sẵn';
                                totalPriceDisplay.textContent = 'Không có sẵn';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching variant price:', error);
                            priceDisplay.textContent = 'Không có sẵn';
                            totalPriceDisplay.textContent = 'Không có sẵn';
                        });
                }
            }

            function updateTotalPrice() {
                const totalPrice = basePrice * quantity;
                totalPriceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(totalPrice) + 'đ';
            }

            function filterOptions() {
                sizeButtons.forEach(button => {
                    const sizeId = button.getAttribute('data-size-id');
                    const isAvailable = variants.some(variant => variant.size_id == sizeId && (!selectedColor || variant.color_id == selectedColor));
                    button.disabled = !isAvailable;
                });

                colorButtons.forEach(button => {
                    const colorId = button.getAttribute('data-color-id');
                    const isAvailable = variants.some(variant => variant.color_id == colorId && (!selectedSize || variant.size_id == selectedSize));
                    button.disabled = !isAvailable;
                });
            }

            sizeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    sizeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    selectedSize = this.getAttribute('data-size-id');
                    filterOptions();
                    updatePrice();
                });
            });

            colorButtons.forEach(button => {
                button.addEventListener('click', function() {
                    colorButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    selectedColor = this.getAttribute('data-color-id');
                    filterOptions();
                    updatePrice();
                });
            });

            decreaseQuantityButton.addEventListener('click', function() {
                if (quantity > 1) {
                    quantity--;
                    quantityInput.value = quantity;
                    updateTotalPrice();
                }
            });

            increaseQuantityButton.addEventListener('click', function() {
                quantity++;
                quantityInput.value = quantity;
                updateTotalPrice();
            });

            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value >= 1) {
                    quantity = value;
                    updateTotalPrice();
                } else {
                    this.value = quantity;
                }
            });

            filterOptions();
        });
    </script>
@endsection
