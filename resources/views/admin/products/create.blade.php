@extends('admin.layout.main')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Thêm mới sản phẩm</h4>
            </div>
            <div class="card-body">
                <!-- Hiển thị thông báo lỗi nếu có -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form thêm sản phẩm -->
                <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Tên sản phẩm -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productName">Tên sản phẩm</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="productName"
                                    name="name" value="{{ old('name') }}" placeholder="Tên sản phẩm" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Giá sản phẩm -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productPrice">Giá tiền (VND)</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="productPrice" name="price" value="{{ old('price') }}" placeholder="Giá"
                                    required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="sku_code" value="{{ old('sku_code', $skuCode) }}">
                    <div class="form-group">
                        <label for="sku_code">Mã SKU sản phẩm</label>
                        <input type="text" class="form-control @error('sku_code') is-invalid @enderror" id="sku_code"
                            value="{{ old('sku_code', $skuCode) }}" placeholder="Mã SKU" readonly>
                        @error('sku_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Thương hiệu -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productBrand">Nhãn hàng</label>
                                <select class="form-control @error('brand_id') is-invalid @enderror" id="productBrand"
                                    name="brand_id" required>
                                    <option value="">Chọn 1 nhãn hàng</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Danh mục -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productCategory">Danh mục</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="productCategory"
                                    name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Trạng thái -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productStatus">Trạng thái</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="productStatus"
                                    name="status" required>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Còn hàng</option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hết hàng</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Chính sách hoàn trả -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="returnPolicy">Chính sách hoàn trả</label>
                                <input type="text" class="form-control @error('return_policy') is-invalid @enderror"
                                    id="returnPolicy" name="return_policy" value="{{ old('return_policy') }}"
                                    placeholder="Nhập">
                                @error('return_policy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hình ảnh chính -->
                    <div class="form-group">
                        <label for="productImage">Ảnh tổng quan</label>
                        <input type="file" class="form-control-file @error('image_url') is-invalid @enderror"
                            id="productImage" name="image_url">
                        <img id="productImagePreview" class="image-preview" src="#" alt="Main Image Preview">
                        @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phần thêm mô tả sản phẩm -->
                    <h5 class="mt-4">Mô tả sản phẩm</h5>
                    <div class="description-section">
                        <!-- Tiêu đề mô tả -->
                        <div class="form-group">
                            <label for="descriptionTitle">Tiêu đề mô tả</label>
                            <input type="text" class="form-control @error('description_title') is-invalid @enderror"
                                id="descriptionTitle" name="description_title" value="{{ old('description_title') }}"
                                placeholder="Mô tả" required>
                            @error('description_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nội dung mô tả -->
                        <div class="form-group">
                            <label for="descriptionContent">Nội dung mô tả</label>
                            <textarea class="form-control @error('description_content') is-invalid @enderror"
                                id="descriptionContent" name="description_content" rows="5"
                                placeholder="Nội dung mô tả"
                                required>{{ old('description_content') }}</textarea>
                            @error('description_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hình ảnh mô tả -->
                        <div class="form-group">
                            <label for="descriptionImage">Ảnh mô tả</label>
                            <input type="file" class="form-control-file @error('description_image') is-invalid @enderror"
                                id="descriptionImage" name="description_image" accept="image/*">
                            @error('description_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Phần thêm biến thể -->
                    <h5 class="mt-4">Biến thể sản phẩm</h5>
                    <div id="variantsContainer">
                        <!-- Biến thể sẽ được thêm động -->
                    </div>

                    <!-- Nút thêm biến thể -->
                    <button type="button" class="btn btn-add-variant mb-3" onclick="addVariant()">
                        <i class="fas fa-plus"></i> Thêm mới
                    </button>

                    <!-- Nút gửi form -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal cảnh báo validate -->
    <div class="modal fade" id="validateModal" tabindex="-1" role="dialog" aria-labelledby="validateModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="validateModalLabel">Cảnh báo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="validateModalBody">
            <!-- Nội dung cảnh báo sẽ được thay đổi bằng JS -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let variantCount = 0;

        // Load danh sách kích thước và màu sắc từ server
        const sizes = @json(\App\Models\Size::all()->pluck('name', 'id'));
        const colors = @json(\App\Models\Color::all()->pluck('name', 'id'));

        // Hàm thêm biến thể mới
        function addVariant() {
            variantCount++;
            const variantHtml = `
                                <div class="variant-section" id="variant-${variantCount}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>Variant ${variantCount}</h6>
                                        <span class="remove-variant" onclick="removeVariant(${variantCount})">
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </span>
                                    </div>
                                    <div class="row">
                                        <!-- Kích thước -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="variantSize-${variantCount}">Kích cỡ</label>
                                                <select class="form-control" id="variantSize-${variantCount}" name="variants[${variantCount}][size_id]" required>
                                                    <option value="">Select a size</option>
                                                    ${Object.keys(sizes).map(id => `<option value="${id}">${sizes[id]}</option>`).join('')}
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Màu sắc -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="variantColor-${variantCount}">Màu</label>
                                                <select class="form-control" id="variantColor-${variantCount}" name="variants[${variantCount}][color_id]" required>
                                                    <option value="">Select a color</option>
                                                    ${Object.keys(colors).map(id => `<option value="${id}">${colors[id]}</option>`).join('')}
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Giá -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="variantPrice-${variantCount}">Giá tiền (VND)</label>
                                                <input type="number" class="form-control" id="variantPrice-${variantCount}" name="variants[${variantCount}][price]" placeholder="Enter price">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Số lượng tồn kho -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="variantStock-${variantCount}">Số lượng</label>
                                                <input type="number" class="form-control" id="variantStock-${variantCount}" name="variants[${variantCount}][stock_quantity]" placeholder="Enter stock quantity">
                                            </div>
                                        </div>
                                        <!-- Hình ảnh biến thể -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="variantImage-${variantCount}">Ảnh biến thể</label>
                                                <input type="file" class="form-control-file" id="variantImage-${variantCount}" name="variants[${variantCount}][image]">
                                                <img id="variantImagePreview-${variantCount}" class="image-preview" src="#" alt="Variant Image Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
            document.getElementById('variantsContainer').insertAdjacentHTML('beforeend', variantHtml);

            // Xem trước hình ảnh biến thể
            document.getElementById(`variantImage-${variantCount}`).addEventListener('change', function (event) {
                const file = event.target.files[0];
                const imagePreview = document.getElementById(`variantImagePreview-${variantCount}`);
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        }

        // Hàm xóa biến thể
        function removeVariant(variantId) {
            document.getElementById(`variant-${variantId}`).remove();
        }

        // Xem trước hình ảnh chính
        document.getElementById('productImage').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const imagePreview = document.getElementById('productImagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });

        // Xem trước hình ảnh mô tả
        document.getElementById('descriptionImage').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const imagePreview = document.getElementById('descriptionImagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });

        document.getElementById('productForm').addEventListener('submit', function(e) {
            // Kiểm tra có ít nhất 1 biến thể
            const variants = document.querySelectorAll('.variant-section');
            if (variants.length < 1) {
                showValidateModal('Yêu cầu ít nhất 1 biến thể sản phẩm.');
                e.preventDefault();
                return false;
            }

            // Kiểm tra giá biến thể >= 50% giá sản phẩm
            const productPrice = parseFloat(document.getElementById('productPrice').value) || 0;
            let valid = true;
            variants.forEach(function(variant) {
                const priceInput = variant.querySelector('input[name*="[price]"]');
                if (priceInput) {
                    const variantPrice = parseFloat(priceInput.value) || 0;
                    if (variantPrice < 0.5 * productPrice) {
                        valid = false;
                    }
                }
            });
            if (!valid) {
                showValidateModal('Giá biến thể phải lớn hơn hoặc bằng 50% giá sản phẩm.');
                e.preventDefault();
                return false;
            }
        });

        // Hàm hiển thị modal cảnh báo
        function showValidateModal(message) {
            document.getElementById('validateModalBody').innerText = message;
            $('#validateModal').modal('show');
        }
    </script>

    <style>
        .image-preview {
            width: 255px;
            height: 255px;
            object-fit: cover;
            /* Cắt ảnh để vừa khung */
            border-radius: 5px;
            margin-top: 10px;
            display: none;
        }

        .variant-section {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #fff;
        }

        .variant-section .remove-variant {
            color: #dc3545;
            cursor: pointer;
        }

        .btn-add-variant {
            background-color: #28a745;
            border: none;
        }

        .btn-add-variant:hover {
            background-color: #218838;
        }

        .description-section {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #fff;
            margin-bottom: 20px;
        }
    </style>
@endsection