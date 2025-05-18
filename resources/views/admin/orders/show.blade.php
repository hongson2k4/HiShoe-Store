@extends('admin.layout.main')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Đơn hàng #{{ $order->order_check }}</h2>
            <div class="order-status-badge">
                <span class="badge text-light {{ $order->getStatusBadgeClass() }}">
                    {{ $order->status_text }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Thông tin khách hàng</h4>
                    <p><strong>Tên:</strong> {{ $order->user->full_name ?? 'Unknown' }}</p>
                    <p><strong>Địa chỉ Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->user->phone_number}}</p>
                </div>
                <div class="col-md-6">
                    <h4>Thông tin đơn hàng</h4>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Tổng đơn:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                    <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                </div>
            </div>

            <h4 class="mt-4">Chi tiết đơn hàng</h4>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Màu sắc</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Tổng cộng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->getProductName() }}</td>
                        <td>{{ $detail->getVariantDetails() }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format($order->orderDetails->sum(fn($detail) => $detail->price * $detail->quantity), 0, ',', '.') }} VNĐ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có thông tin về đơn hàng này !</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($order->canChangeStatus())
            <div class="card-footer">
                <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <select name="status" class="form-select">
                                @foreach(\App\Models\Order::getStatusList() as $key => $value)
                                @if(in_array($key,$order->getAllowedNextStatuses($order->status)) || $key == $order->status)
                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">
                                Cập nhập trạng thái
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
            <a href="{{ route('orders.index') }}" class="btn btn-secondary m-2">
                Quay về danh sách đơn hàng
            </a>
        </div>
    </div>
</div>
@endsection