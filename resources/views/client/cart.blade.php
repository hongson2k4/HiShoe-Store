@extends('client.layout.main')
@section('title')
HiShoe-Store - Giỏ hàng
@endsection
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>

    @if(count($cartItems) > 0)
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-4">Sản phẩm</th>
                                    <th class="py-3">Kích cỡ</th>
                                    <th class="py-3">Màu sắc</th>
                                    <th class="py-3">Đơn giá</th>
                                    <th class="py-3">Số lượng</th>
                                    <th class="py-3">Thành tiền</th>
                                    <th class="py-3 pe-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr class="border-bottom">
                                    <td class="py-4 ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-3 overflow-hidden me-3" style="width: 80px; height: 80px;">
                                                <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="img-fluid object-fit-cover h-100 w-100">
                                            </div>
                                            <div>
                                                <h6 class="fw-semibold mb-0">{{ $item->product->name }}</h6>
                                                <span class="badge bg-light text-secondary">{{ $item->product->brand->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4"><span class="badge bg-light text-dark">{{ $item->productVariant->size->name }}</span></td>
                                    <td class="py-4">
                                        <div class="d-flex align-items-center">
                                            <span class="color-circle me-2"></span>
                                            <span>{{ $item->productVariant->color->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 fw-medium">{{ number_format($item->productVariant->price) }}đ</td>
                                    <td class="py-4">
                                        <div class="quantity-control d-flex align-items-center" style="width: 120px;">
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle update-cart" data-id="{{ $item->id }}" data-action="decrease">
                                                <i class="fa fa-minus small"></i>
                                            </button>
                                            <input type="text" class="form-control form-control-sm text-center border-0 bg-light mx-2 item-quantity" value="{{ $item->quantity }}" readonly>
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle update-cart" data-id="{{ $item->id }}" data-action="increase">
                                                <i class="fa fa-plus small"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-4 fw-bold text-primary">{{ number_format($item->productVariant->price * $item->quantity) }}đ</td>
                                    <td class="py-4 pe-4">
                                        <form action="{{ route('cart.remove') }}" method="POST" id="removeItemForm{{ $item->id }}">
                                            @csrf
                                            <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle"
                                                data-bs-toggle="tooltip" data-bs-title="Xóa sản phẩm"
                                                onclick="confirmRemoveItem('{{ $item->id }}', '{{ $item->product->name }}')">
                                                <i class="fa fa-trash"></i>
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

            <div class="d-flex justify-content-between">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fa fa-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
                <form action="{{ route('cart.clear') }}" method="POST" id="clearCartForm">
                    @csrf
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4" onclick="confirmClearCart()">
                        <i class="fa fa-trash me-2"></i>Xóa giỏ hàng
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-0 pt-4 ps-4">
                    <h5 class="fw-bold mb-0">Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Tạm tính:</span>
                        <span>{{ number_format($subtotal) }}đ</span>
                    </div>

                    @if($discount > 0)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Giảm giá:</span>
                        <span class="text-success">-{{ number_format($discount) }}đ</span>
                    </div>
                    @endif

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-primary fs-5">{{ number_format($total) }}đ</strong>
                    </div>

                    <!-- Voucher input -->
                    <form action="{{ route('cart.apply-voucher') }}" method="POST" class="mb-4">
                        @csrf
                        <label class="form-label small fw-medium mb-2">Mã giảm giá</label>
                        <div class="input-group input-group-lg">
                            <input type="text" name="voucher_code" class="form-control rounded-start-pill" placeholder="Nhập mã giảm giá">
                            <button type="submit" class="btn btn-outline-primary rounded-end-pill px-3">Áp dụng</button>
                        </div>
                        @if(session('voucher_error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-2 py-2 small" role="alert">
                            {{ session('voucher_error') }}
                            <button type="button" class="btn-close small" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if(session('voucher_success'))
                        <div class="alert alert-success alert-dismissible fade show mt-2 py-2 small" role="alert">
                            {{ session('voucher_success') }}
                            <button type="button" class="btn-close small" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                    </form>

                    <a href="#" class="btn btn-primary btn-lg w-100 rounded-pill fw-medium">
                        <i class="fa fa-credit-card me-2"></i>Tiến hành thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5 my-5">
        <div class="mb-4">
            <i class="fa fa-shopping-cart fa-5x text-light mb-3"></i>
        </div>
        <h4 class="fw-bold mb-3">Giỏ hàng của bạn đang trống</h4>
        <p class="text-muted mb-4">Hãy thêm một vài sản phẩm và quay lại đây nhé!</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill px-5">
            <i class="fa fa-shoe-prints me-2"></i>Khám phá sản phẩm
        </a>
    </div>
    @endif
</div>

<!-- Toast notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="cartToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa fa-check-circle me-2"></i>
                <span id="toastMessage">Giỏ hàng đã được cập nhật</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Cart update JS -->
<script>
    function confirmRemoveItem(itemId, productName) {
        if (confirm(`Bạn có chắc chắn muốn xóa "${productName}" khỏi giỏ hàng không?`)) {
            document.getElementById('removeItemForm' + itemId).submit();
        }
    }
    function confirmClearCart() {
        if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng không?')) {
            document.getElementById('clearCartForm').submit();
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('.update-cart');

        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.getAttribute('data-id');
                const action = this.getAttribute('data-action');

                fetch('{{ route("cart.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cart_id: cartId,
                            action: action
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
            });
        });
    });
</script>
@endsection