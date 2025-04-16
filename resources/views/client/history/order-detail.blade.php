@extends('client.layout.main')

@section('content')
<div class="container" style="margin-top: 150px;">
    <!-- Thông báo -->
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

    <!-- Tiêu đề -->
    <h2>Chi tiết đơn hàng #{{ $order->order_check }}</h2>
    <p><strong>Mã đơn hàng:</strong> {{ $order->order_check }}</p>
    <p><strong>Tên khách hàng:</strong> {{ $order->user->full_name }}</p>
    <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->orderItemHistories->sum(fn($item) => $item->price * $item->quantity), 0, ',', '.') }} VNĐ</p>
    <p><strong>Trạng thái:</strong>
        <span class="badge {{ $order->getStatusClass() }} text-white p-2 fs-6 rounded-pill fw-normal">
            {{ $order->status_text }}
        </span>
    </p>
    @if ($order->status == 5 && $order->customer_reasons)
        <p><strong>Lý do hủy:</strong> {{ $order->customer_reasons }}</p>
    @endif

    <!-- Danh sách sản phẩm trong đơn hàng -->
    <h3 class="mt-4">Sản phẩm trong đơn</h3>
    @if ($order->orderItemHistories->isEmpty())
        <p>Không có sản phẩm nào trong đơn hàng.</p>
    @else
        <div class="card mb-4">
            <div class="card-body">
                @foreach ($order->orderItemHistories as $item)
                    <div class="row mb-3 border-bottom pb-3">
                        <!-- Hình ảnh sản phẩm -->
                        @if ($item->product && $item->product->image_url)
                            <div class="col-md-2">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="img-fluid" style="max-width: 100px;">
                            </div>
                        @else
                            <div class="col-md-2">
                                <img src="https://via.placeholder.com/100" alt="No image" class="img-fluid" style="max-width: 100px;">
                            </div>
                        @endif
                        <!-- Thông tin sản phẩm -->
                        <div class="col-md-10">
                            <h5>{{ $item->product ? $item->product->name : 'Sản phẩm không tồn tại' }}</h5>
                            <p><strong>Mô tả:</strong> {{ $item->product ? ($item->product->description ?? 'Không có mô tả') : 'Không có mô tả' }}</p>
                            <p><strong>Giá:</strong> {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                            <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
                            <p><strong>Thành tiền:</strong> {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</p>
                            <!-- Nút Mua lại - Chỉ hiển thị khi status là 5, 6, hoặc 7 -->
                            @if ($item->product && in_array($order->status, [5, 6, 7]))
                                <a href="{{ route('product.detail', $item->product->id) }}" class="btn btn-danger btn-sm">Mua lại</a>
                            @elseif (!$item->product)
                                <button class="btn btn-secondary btn-sm" disabled>Không khả dụng</button>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Tổng giá tiền phải thanh toán -->
                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <h4><strong>Tổng giá tiền phải thanh toán: {{ number_format($order->orderItemHistories->sum(fn($item) => $item->price * $item->quantity), 0, ',', '.') }} VNĐ</strong></h4>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Nút quay lại -->
    <div class="mt-3">
        <a href="{{ route('order-history') }}" class="btn text-white" style="background-color: #F89CAB;">Back to history</a>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .card-body {
            padding: 20px;
        }
        .img-fluid {
            border-radius: 8px;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
        }
        .border-bottom {
            border-bottom: 1px solid #ddd;
        }
        .text-end {
            text-align: right;
        }
    </style>
@endpush