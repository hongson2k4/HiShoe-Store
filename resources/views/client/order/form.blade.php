<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra Cứu Đơn Hàng</title>
</head>
<body>
    <h2>Nhập mã đơn hàng để kiểm tra</h2>
    <form action="{{ route('order.track') }}" method="POST">
        @csrf
        <input type="text" name="order_check" placeholder="Nhập mã đơn hàng">
        <button type="submit">Kiểm tra</button>
    </form>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @isset($order)
        <h3>Thông tin đơn hàng</h3>
        <p>Mã đơn hàng: {{ $order->order_check }}</p>
        {{-- <p>Trạng thái: {{ $order->status }}</p> --}}
        @php
        $statusText = [
            1 => 'Đơn Hàng Đã Đặt',
            2 => 'Đang Đóng Gói',
            3 => 'Đang Vận Chuyển',
            4 => 'Đã Giao Hàng',
            5 => 'Đã Hủy Đơn',
            6 => 'Trả Hàng'
        ];
    
        $statusColor = [
            1 => 'green',
            2 => 'green',
            3 => 'green',
            4 => 'green',
            5 => 'red',
            6 => 'gray'
        ];
    @endphp
    
    <p>Trạng thái: 
        <span style="background-color: {{ $statusColor[$order->status] ?? 'black' }}; 
                     color: white; 
                     padding: 5px 10px; 
                     border-radius: 5px; 
                     display: inline-block;">
            {{ $statusText[$order->status] ?? 'Không xác định' }}
        </span>
    </p>
    
        {{-- Truyền $order vào status.blade.php --}}
        @include('client.order.status', ['order' => $order])
    @endisset

    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
</body>
</html>
