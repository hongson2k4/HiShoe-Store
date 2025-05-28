@extends('client.layout.main')

@section('title', 'Chi tiết sản phẩm - ' . $products->name)

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
            flex: 0 0 25%;
            /* Hiển thị 4 sản phẩm */
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

    <style>
        .product-image {
            width: 100%;
            border-radius: 10px;
        }

        .variant-button.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .related-products {
            margin-top: 50px;
        }
    </style>

    <style>
        .variant-button.active {
            background-color: #007bff !important;
            color: #fff !important;
            border-color: #007bff !important;
        }

        .variant-button {
            border-color: #007bff !important;
            color: #007bff !important;
            background-color: #fff !important;
            transition: background 0.2s, color 0.2s;
        }

        .variant-button:hover:not(:disabled):not(.active) {
            background-color: #e6f0ff !important;
            color: #0056b3 !important;
            border-color: #007bff !important;
        }

        .variant-button:disabled {
            background-color: #e0e0e0 !important;
            color: #a0a0a0 !important;
            border-color: #ccc !important;
            cursor: not-allowed;
        }
    </style>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-6 col-md-12 text-center">
                <img id="mainProductImage" src="{{ Storage::url($products->image_url) }}" class="product-image img-fluid"
                    alt="{{ $products->name }}">
                <div class="d-flex justify-content-center mt-3" id="productGallery">
                    <!-- Ảnh chính -->
                    <img src="{{ Storage::url($products->image_url) }}" class="img-thumbnail mx-1 gallery-thumb"
                        data-main-image="{{ Storage::url($products->image_url) }}" data-size-id="" data-color-id=""
                        style="width:70px; height:70px; object-fit:cover; cursor:pointer;">
                    <!-- Ảnh biến thể -->
                    @foreach($variantImages as $img)
                        <img src="{{ Storage::url($img['image_url']) }}" class="img-thumbnail mx-1 gallery-thumb"
                            data-main-image="{{ Storage::url($img['image_url']) }}" data-size-id="{{ $img['size_id'] }}"
                            data-color-id="{{ $img['color_id'] }}"
                            style="width:70px; height:70px; object-fit:cover; cursor:pointer;">
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="p-4 border rounded bg-white shadow-sm">
                    <h2 class="text-primary">{{ $products->name }}</h2>
                    <p class="text-muted">Mã sản phẩm: {{ $products->id }}</p>
                    <p>Giá sản phẩm:</p>
                    <h5 class="text-danger">{{ number_format($products->price, 0, ',', '.') }} VNĐ</h5>
                    <p>Thương hiệu: {{ $products->brand->name }}</p>
                    <p>Danh mục: {{ $products->category->name }}</p>

                    <div class="variant-selector">
                        <p>Chọn kích cỡ:</p>
                        <div id="sizeButtons">
                            @foreach($availableSizes as $id => $name)
                                <button class="btn btn-outline-secondary m-1 variant-button"
                                    data-size-id="{{ $id }}">{{ $name }}</button>
                            @endforeach
                        </div>
                        <p>Chọn màu sắc:</p>
                        <div id="colorButtons">
                            @foreach($availableColors as $id => $name)
                                <button class="btn btn-outline-secondary m-1 variant-button"
                                    data-color-id="{{ $id }}">{{ $name }}</button>
                            @endforeach
                        </div>
                    </div>

                    <p id="stockInfo" class="text-muted">Chọn kích cỡ và màu sắc để xem số lượng hàng trong kho</p>

                    <div class="input-group my-3" style="width: 120px;">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" id="decreaseQuantity">-</button>
                        </div>
                        <input type="number" class="form-control text-center" value="1" min="1" id="quantityInput">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="increaseQuantity">+</button>
                        </div>
                    </div>

                    <h4 class="text-danger mt-3" id="totalPrice">{{ number_format($products->price, 0, ',', '.') }} VNĐ</h4>

                    <button id="addToCartButton" class="btn btn-success mt-3 w-100">Thêm vào giỏ hàng</button>
                </div>
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
                        <img src="{{ Storage::url($products->productDetails->first()->detail_image) }}" alt="Description Image"
                            class="img-fluid rounded shadow-sm">
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card bg-light p-4 mb-4 mt-4 shadow-sm">
        <h4 class="fw-semibold mb-4">Đánh giá & Bình luận</h4>

        {{-- Hiển thị đánh giá --}}
        @foreach ($reviews as $review)
            <div class="media mb-4 border-bottom pb-3">
                <img class="mr-3 rounded-circle border"
                    src="{{ $review->user->avatar ?? 'https://cdn.kona-blue.com/upload/kona-blue_com/post/images/2024/09/19/465/avatar-trang-1.jpg' }}"
                    alt="Avatar" width="50" height="50">
                <div class="media-body">
                    <h5 class="mt-0 mb-1">{{ $review->user->full_name }}</h5>
                    <small class="text-muted d-block mb-2">Đánh giá lúc {{ $review->created_at->format('d/m/Y H:i') }}</small>
                    <div class="mb-2">
                        {{-- Hiển thị số sao --}}
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                <span style="color: #ffc107;">&#9733;</span>
                            @else
                                <span style="color: #e4e5e9;">&#9733;</span>
                            @endif
                        @endfor
                    </div>
                    <p class="mb-2">{{ $review->comment }}</p>
                </div>
            </div>
        @endforeach

        {{-- Hiển thị bình luận --}}
        @foreach ($comments as $cmt)
            <div class="media mb-4 border-bottom pb-3">
                <img class="mr-3 rounded-circle border"
                    src="{{ $cmt->user->avatar ?? 'https://cdn.kona-blue.com/upload/kona-blue_com/post/images/2024/09/19/465/avatar-trang-1.jpg' }}"
                    alt="Avatar" width="50" height="50">
                <div class="media-body">
                    <h5 class="mt-0 mb-1">{{ $cmt->user->full_name }}</h5>
                    <small class="text-muted d-block mb-2">Lúc {{ $cmt->created_at->format('d/m/Y H:i') }}</small>
                    <p class="mb-2">{{ $cmt->content }}</p>
                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-reply"></i> Trả lời</a>
                </div>
            </div>
        @endforeach

        {{-- Form gửi bình luận --}}
        <div class="mt-4">
            <form action="{{URL('comment/send')}}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $products->id }}">
                <input type="hidden" name="user_id" value="{{ Auth::check() ? Auth::guard('web')->user()->id : '' }}">
                <div class="form-group">
                    <textarea class="form-control" rows="4" name="content"
                        placeholder="Nhập nội dung bình luận..."></textarea>
                </div>
                <button class="btn btn-success btn-block font-weight-bold mt-2">GỬI BÌNH LUẬN</button>
            </form>
        </div>
    </div>

    <div class="related-products mt-5">
        <h3 class="text-center">Sản phẩm gợi ý</h3>
        <div id="relatedCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($products as $index => $product)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('detail', $products->id) }}" class="text-decoration-none text-dark">
                                <img src="{{ Storage::url($products->image_url) }}" class="d-block" style="max-width: 200px;"
                                    alt="{{ $products->name }}">
                                <h6 class="mt-2 text-center">{{ $products->name }}</h6>
                                <p class="text-danger text-center">{{ number_format($products->price, 0, ',', '.') }} VNĐ</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#relatedCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#relatedCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>

    <script>
        // Đặt các biến toàn cục ở ngoài
        let sizeButtons, colorButtons, selectedSize = null, selectedColor = null, variants, updateStockInfo, updateColorButtonStates, updateSizeButtonStates;

        document.addEventListener('DOMContentLoaded', function () {
            sizeButtons = document.querySelectorAll('#sizeButtons .variant-button');
            colorButtons = document.querySelectorAll('#colorButtons .variant-button');
            variants = @json($variants);

            updateStockInfo = function() {
                const selectedSizeId = selectedSize;
                const selectedColorId = selectedColor;

                let stockInfoText = 'Không có sẵn';
                let maxStock = 0;
                let price = {{ $products->price }};

                if (selectedSizeId && selectedColorId) {
                    const matchingVariant = variants.find(variant =>
                        variant.size_id == selectedSizeId && variant.color_id == selectedColorId
                    );

                    if (matchingVariant) {
                        maxStock = matchingVariant.stock_quantity;
                        price = matchingVariant.price;
                        stockInfoText = `Còn ${maxStock} sản phẩm`;
                    }
                }

                stockInfo.textContent = stockInfoText;
                quantityInput.max = maxStock;

                // Định dạng giá tiền đẹp hơn
                totalPrice.innerHTML = `<span style="font-size:1.5rem;">
                                    ${Number(price).toLocaleString('vi-VN')} <span style="font-size:1rem;">VNĐ</span>
                                </span>`;

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
                button.addEventListener('click', function () {
                    // Nếu đã chọn, nhấn lại sẽ hủy chọn
                    if (this.classList.contains('active')) {
                        this.classList.remove('active');
                        selectedSize = null;
                        resetVariantButtons();
                        updateStockInfo();
                        return;
                    }
                    sizeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    selectedSize = this.getAttribute('data-size-id');
                    updateStockInfo();
                    updateColorButtonStates();
                });
            });

            colorButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Nếu đã chọn, nhấn lại sẽ hủy chọn
                    if (this.classList.contains('active')) {
                        this.classList.remove('active');
                        selectedColor = null;
                        resetVariantButtons();
                        updateStockInfo();
                        return;
                    }
                    colorButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    selectedColor = this.getAttribute('data-color-id');
                    updateStockInfo();
                    updateSizeButtonStates();
                });
            });

            // Disable color buttons if no variant exists for selected size
            updateColorButtonStates = function() {
                colorButtons.forEach(button => {
                    const colorId = button.getAttribute('data-color-id');
                    const exists = variants.some(variant =>
                        variant.size_id == selectedSize && variant.color_id == colorId
                    );
                    button.disabled = !exists;
                    if (!exists) button.classList.remove('active');
                });
            }

            // Disable size buttons if no variant exists for selected color
            updateSizeButtonStates = function() {
                sizeButtons.forEach(button => {
                    const sizeId = button.getAttribute('data-size-id');
                    const exists = variants.some(variant =>
                        variant.size_id == sizeId && variant.color_id == selectedColor
                    );
                    button.disabled = !exists;
                    if (!exists) button.classList.remove('active');
                });
            }

            // Khi chưa chọn gì, enable tất cả
            function resetVariantButtons() {
                sizeButtons.forEach(btn => btn.disabled = false);
                colorButtons.forEach(btn => btn.disabled = false);
            }

            // Gọi reset khi load trang
            resetVariantButtons();

            decreaseQuantityButton.addEventListener('click', function () {
                if (quantity > 1) {
                    quantity--;
                    quantityInput.value = quantity;
                }
            });

            increaseQuantityButton.addEventListener('click', function () {
                quantity++;
                validateQuantity();
            });

            quantityInput.addEventListener('input', function () {
                const value = parseInt(this.value);
                if (value >= 1) {
                    quantity = value;
                    validateQuantity();
                } else {
                    this.value = quantity;
                }
            });

            addToCartButton.addEventListener('click', function () {
                // Kiểm tra đăng nhập trước khi thêm vào giỏ hàng
                @if (!Auth::check())
                    alert('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
                    window.location.href = "{{ route('loginForm') }}";
                    return;
                @endif

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainProductImage = document.getElementById('mainProductImage');
            const galleryThumbs = document.querySelectorAll('.gallery-thumb');

            // Khi click vào ảnh nhỏ
            galleryThumbs.forEach(function (thumb) {
                thumb.addEventListener('click', function () {
                    // Đổi ảnh lớn
                    mainProductImage.src = this.getAttribute('data-main-image');
                    // Nếu là ảnh biến thể thì tự chọn size & color
                    const sizeId = this.getAttribute('data-size-id');
                    const colorId = this.getAttribute('data-color-id');
                    if (sizeId && colorId) {
                        // Tự chọn nút size
                        sizeButtons.forEach(btn => {
                            if (btn.getAttribute('data-size-id') == sizeId) {
                                btn.classList.add('active');
                                selectedSize = sizeId;
                            } else {
                                btn.classList.remove('active');
                            }
                        });
                        // Tự chọn nút color
                        colorButtons.forEach(btn => {
                            if (btn.getAttribute('data-color-id') == colorId) {
                                btn.classList.add('active');
                                selectedColor = colorId;
                            } else {
                                btn.classList.remove('active');
                            }
                        });
                        if (typeof updateStockInfo === 'function') updateStockInfo();
                        if (typeof updateColorButtonStates === 'function') updateColorButtonStates();
                        if (typeof updateSizeButtonStates === 'function') updateSizeButtonStates();
                    }
                });
            });

            function updateMainImageByVariant() {
                if (selectedSize && selectedColor) {
                    const matchingVariant = variants.find(variant =>
                        variant.size_id == selectedSize && variant.color_id == selectedColor
                    );
                    if (matchingVariant && matchingVariant.image_url) {
                        mainProductImage.src = '/storage/' + matchingVariant.image_url.replace(/^public\//, '');
                    } else {
                        mainProductImage.src = "{{ Storage::url($products->image_url) }}";
                    }
                }
            }

            if (sizeButtons && colorButtons) {
                sizeButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        setTimeout(updateMainImageByVariant, 10);
                    });
                });
                colorButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        setTimeout(updateMainImageByVariant, 10);
                    });
                });
            }
        });
    </script>

@endsection