@extends('client.layout.main')

@section('content')
<div class="container" style="margin-top: 150px;">
    <h2>Chi tiết đơn hàng #{{ $order->order_check }}</h2>
    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
    <p><strong>Trạng thái:</strong>
        <span class="badge {{ $order->getStatusClass() }} text-white p-2 fs-6 rounded-pill fw-normal">
            {{ $order->status_text }}
        </span>

    <h3>Sản phẩm trong đơn</h3>
    <table>
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItemHistories as $item)
                <tr>
                    <td>{{ optional($item->product)->name ?? 'Sản phẩm không tồn tại' }}</td>
                    <td>{{ number_format(optional($item->product)->price ?? 0, 0, ',', '.') }} VNĐ</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((optional($item->product)->price ?? 0) * $item->quantity, 0, ',', '.') }} VNĐ</td>
                    <td>
                        @if ($item->product)
                            <a href="{{ route('product.detail', $item->product->id) }}" class="btn btn-success">Mua lại</a>
                        @else
                            <button class="btn btn-secondary" disabled>Không khả dụng</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        <a href="{{ route('order-history') }}" class="btn text-white" style="background-color: #F89CAB;">Back to history</a>
    </div>
</div>
@endsection
