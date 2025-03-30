@extends('client.layout.main')

@section('content')
<div class="container" style="margin-top: 100px;">
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
    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
    <p><strong>Trạng thái:</strong>
        <span class="badge {{ $order->getStatusClass() }} text-white p-2 fs-6 rounded-pill fw-normal">
            {{ $order->status_text }}
        </span>
    </p>
    @if ($order->status == 5 && $order->customer_reasons)
        <p><strong>Lý do hủy:</strong> {{ $order->customer_reasons }}</p>
    @endif

    <!-- Thông tin sản phẩm trong đơn hàng -->
    <h3 class="mt-4">Sản phẩm trong đơn</h3>
    @if ($order->product)
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Hình ảnh sản phẩm -->
                    @if ($order->product->image_url)
                        <div class="col-md-3">
                            <img src="{{ $order->product->image_url }}" alt="{{ $order->product->name }}" class="img-fluid" style="max-width: 150px;">
                        </div>
                    @endif
                    <!-- Thông tin sản phẩm -->
                    <div class="col-md-9">
                        <h5>{{ $order->product->name }}</h5>
                        <p><strong>Mô tả:</strong> {{ $order->product->description ?? 'Không có mô tả' }}</p>
                        <p><strong>Giá:</strong> {{ number_format($order->product->price, 0, ',', '.') }} VNĐ</p>
                        <p><strong>Số lượng còn lại:</strong> {{ $order->product->stock_quantity }}</p>
                        <!-- Nút Mua lại -->
                        <a href="{{ route('product.detail', $order->product->id) }}" class="btn btn-outline-danger btn-sm">Mua lại</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>Không có sản phẩm nào trong đơn hàng.</p>
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
    </style>
@endpush