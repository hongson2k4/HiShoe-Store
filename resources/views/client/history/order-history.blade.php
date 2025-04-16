@extends('client.layout.main')

@section('content')
<div class="container">
    <!-- Thêm thông báo -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- <h1 class="text-center mb-5">Lịch sử đơn hàng</h1> --}}

    <!-- Form lọc -->
    <form action="{{ route('order-history') }}" method="GET" class="filter-form mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Nhập mã đơn hàng">
                    <label for="order_id">Mã đơn hàng</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="day" name="day" placeholder="Nhập ngày">
                    <label for="day">Ngày</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="month" name="month" placeholder="Nhập tháng">
                    <label for="month">Tháng</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="year" name="year" placeholder="Nhập năm">
                    <label for="year">Năm</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <select class="form-select" id="status" name="status">
                        <option value="" selected>Chọn trạng thái</option>
                        <option value="1">Đơn đã đặt</option>
                        <option value="2">Đang đóng gói</option>
                        <option value="3">Đang vận chuyển</option>
                        <option value="4">Đã giao hàng</option>
                        <option value="5">Đã hủy</option>
                        <option value="6">Đã trả hàng</option>
                        <option value="7">Đã Nhận hàng</option>
                    </select>
                    <label for="status">Trạng thái</label>
                </div>
            </div>
    
            <div class="col-auto">
                <button type="submit" class="btn text-white" style="background-color: #EC7FA9;">Lọc</button>
            </div>
        </div>
    </form>

    @if ($orders->isEmpty())
        <p>Bạn chưa có đơn hàng nào.</p>
    @else
        <!-- Table Header -->
        <div class="order-header row align-items-center mb-3">
            <div class="col-md-6">
                <h6 class="mb-0" style="color: white;">Tên sản phẩm</h6>
            </div>
            <div class="col-md-2">
                <h6 class="mb-0" style="color: white;">Mã đơn hàng</h6>
            </div>
            <div class="col-md-2">
                <h6 class="mb-0" style="color: white;">Ngày đặt</h6>
            </div>
            <div class="col-md-2">
                <h6 class="mb-0" style="color: white;">Trạng thái</h6>
            </div>
        </div>

        <div class="orders-list">
            @foreach ($orders as $order)
                <div class="card mb-3 order-card" data-url="{{ route('order.history.detail', $order->id) }}" style="cursor: pointer;">
                    <div class="card-body p-3">
                        <!-- Main info row -->
                        <div class="row align-items-center mb-3">
                            <div class="col-md-6">
                                <div class="product-info">
                                    @if ($order->orderItemHistories->isNotEmpty())
                                        <!-- Hiển thị danh sách sản phẩm -->
                                        @foreach ($order->orderItemHistories as $item)
                                            <div class="product-item d-flex align-items-center mb-2">
                                                <!-- Ảnh sản phẩm -->
                                                @if (!empty($item->product->image_url))
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-image me-2" style="height: 100px; width: auto;">
                                                @else
                                                    <img src="https://via.placeholder.com/40" alt="Placeholder" class="product-image me-2">
                                                @endif
                                                <!-- Tên sản phẩm và số lượng -->
                                                <div class="product-details flex-grow-1">
                                                    <span class="text-black d-block text-truncate">
                                                        {{ $item->product->name }}
                                                    </span>
                                                    <span class="text-muted small">Số lượng: {{ $item->quantity }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        <!-- Tổng số sản phẩm -->
                                        <div class="total-items text-muted small">
                                            (Tổng: {{ $order->totalItems }} sản phẩm)
                                        </div>
                                    @else
                                        <span>Không có sản phẩm</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="order-id">
                                    <span class="order-id-text">{{ $order->order_check }}</span>
                                    <span class="copy-notice" data-default-text="Click to copy">Click to copy</span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="order-date">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span class="badge {{ $order->getStatusClass() }} text-white p-2 fs-6 rounded-pill fw-normal">
                                    {{ $order->status_text }}
                                </span>
                            </div>
                        </div>

                        <!-- Total price and actions row -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="order-total">
                                        <strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ
                                    </div>
                                    <div class="action-buttons">
                                        @if ($order->canCancel())
                                            <button type="button" class="btn btn-danger btn-sm cancel-order-btn" data-bs-toggle="modal" data-bs-target="#cancelOrderModal" data-order-id="{{ $order->id }}">Hủy đơn hàng</button>
                                        @elseif ($order->status == 1 && $order->isOver7Days())
                                            @if ($order->needs_support)
                                                <span class="text-muted">Shop sẽ liên lạc cho bạn!</span>
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm contact-shop-btn" data-order-id="{{ $order->id }}">Liên hệ shop</button>
                                            @endif
                                        @elseif ($order->status == 2 || $order->status == 3)
                                            @if ($order->needs_support)
                                                <span class="text-muted">Shop sẽ liên lạc cho bạn!</span>
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm contact-shop-btn" data-order-id="{{ $order->id }}">Liên hệ shop</button>
                                            @endif
                                        @elseif ($order->status == 4)
                                            <form action="{{ route('order.history.receive', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn đã nhận được hàng?');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Đã nhận được hàng</button>
                                            </form>
                                        @elseif ($order->status == 7)
                                            <div class="d-flex gap-2">
                                                @if ($order->needs_refunded)
                                                    <span class="text-warning">Đang xem xét</span>
                                                @elseif (($order->is_reviewed != 1) && ($order->is_refunded != 1))
                                                    <form action="{{ route('order.history.review', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn muốn đánh giá sản phẩm?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm">Đánh giá sản phẩm</button>
                                                    </form>
                                                    <form action="{{ route('order.history.refund', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn yêu cầu trả hàng/hoàn tiền?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm">Trả hàng/Hoàn tiền</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('order.history.rebuy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có muốn mua lại đơn hàng này?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm btn-rebuy">Mua lại</button>
                                                </form>
                                            </div>
                                        @elseif ($order->status == 5 || $order->status == 6)
                                            <form action="{{ route('order.history.rebuy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có muốn mua lại đơn hàng này?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm btn-rebuy">Mua lại</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Không thể hủy</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="mt-3">
        <a href="{{ route('home') }}" class="btn text-white" style="background-color: #EC7FA9;">Back to Home</a>
    </div>
</div>

<!-- Modal để nhập lý do hủy -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Lý do hủy đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cancelOrderForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Vui lòng nhập lý do hủy đơn hàng:</label>
                        <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script xử lý modal và gửi form -->
<script>
    document.querySelectorAll('.cancel-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const form = document.getElementById('cancelOrderForm');
            form.action = '{{ route("order.history.cancel", ":id") }}'.replace(':id', orderId);
        });
    });
</script>

<!-- Script xử lý liên hệ shop -->
<script>
    document.querySelectorAll('.contact-shop-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            
            if (confirm('Bạn có muốn liên hệ với shop không?')) {
                fetch('{{ route('order.history.contact') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: orderId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        button.outerHTML = '<span class="text-muted">Shop sẽ liên lạc cho bạn! trong thời gian sớm nhất!!</span>';
                        alert('Đã báo với admin');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi gửi yêu cầu: ' + error.message);
                });
            }
        });
    });
</script>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Xử lý sự kiện click trên card
        document.querySelectorAll('.order-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Các khu vực không nên kích hoạt chuyển trang
                const stopPropagationAreas = [
                    '.action-buttons', // Các nút như "Hủy đơn hàng", "Liên hệ shop", v.v.
                    '.order-id',       // Mã đơn hàng (vì có "Click to copy")
                    '.badge'           // Trạng thái (có thể có tương tác riêng)
                ];

                // Kiểm tra xem có nhấp vào các khu vực cần ngăn chuyển trang không
                let shouldStop = false;
                stopPropagationAreas.forEach(area => {
                    if (e.target.closest(area)) {
                        shouldStop = true;
                    }
                });

                // Nếu nhấp vào các khu vực trên, ngăn chuyển trang
                if (shouldStop) {
                    e.stopPropagation();
                    return;
                }

                // Nếu nhấp vào khoảng trống hoặc các khu vực khác, chuyển trang
                const url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Xử lý click to copy cho mã đơn hàng
        document.querySelectorAll('.order-id-text').forEach(orderId => {
            orderId.addEventListener('click', function(e) {
                e.stopPropagation(); // Ngăn chuyển trang

                // Sao chép mã đơn hàng vào clipboard
                const textToCopy = this.textContent.trim();
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Hiển thị thông báo "Copied"
                    const notice = this.parentElement.querySelector('.copy-notice');
                    notice.textContent = 'Copied';
                    notice.classList.add('visible');

                    // Ẩn thông báo sau 2 giây và đặt lại nội dung
                    setTimeout(() => {
                        notice.classList.remove('visible');
                        setTimeout(() => {
                            notice.textContent = notice.getAttribute('data-default-text');
                        }, 300);
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    alert('Không thể sao chép mã đơn hàng. Vui lòng thử lại.');
                });
            });
        });
    </script>
    <style>
        .order-header {
            background-color: #BE5985;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            width: 100%; /* Đảm bảo chiều ngang khớp với card */
            margin: 0; /* Loại bỏ margin thừa */
        }
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .order-card {
            border-radius: 4px;
            transition: transform 0.2s;
            margin-bottom: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: flex-end;
        }
        .action-buttons form {
            margin: 0;
        }
        .action-buttons button {
            white-space: nowrap;
            min-width: fit-content;
        }
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        .action-buttons .d-flex {
            gap: 0.5rem;
            flex-wrap: nowrap;
        }
        .product-info {
            padding: 0.5rem 0;
        }
        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .product-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 0.5rem;
        }
        .product-details {
            flex-grow: 1;
        }
        .total-items {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
        .order-id, .order-date {
            padding: 0.5rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            position: relative;
        }
        .order-id-text {
            cursor: pointer;
            transition: color 0.3s;
        }
        .order-id-text:hover {
            color: #EC7FA9; /* Màu hồng nhạt khi hover */
        }
        .order-id:hover .copy-notice {
            display: block;
            opacity: 1;
        }
        .copy-notice {
            display: none;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 100%;
            transform: translateY(-50%);
            margin-left: 8px;
            padding: 4px 8px;
            background-color: #EC7FA9;
            color: white;
            font-size: 0.75rem;
            border-radius: 4px;
            white-space: nowrap;
            transition: opacity 0.3s ease, background-color 0.3s ease;
            z-index: 10;
        }
        .copy-notice.visible {
            display: block;
            opacity: 1;
        }
        .copy-notice:hover {
            background-color: #D66A93; /* Màu đậm hơn khi hover */
        }
        .order-total {
            font-size: 1rem;
            color: #000;
            padding: 0.5rem 0;
        }
        .card-body {
            padding: 1rem;
        }
        .card-body .row {
            margin: 0;
            align-items: center;
        }
        .badge {
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .row > [class*='col-'] {
                margin-bottom: 0.5rem;
            }
            .order-header {
                display: none;
            }
            .action-buttons {
                justify-content: flex-start;
            }
            .copy-notice {
                position: static;
                margin-left: 4px;
                transform: none;
                display: none;
            }
            .copy-notice.visible {
                display: inline-block;
            }
            .product-image {
                width: 32px;
                height: 32px;
            }
        }
    </style>
@endpush
