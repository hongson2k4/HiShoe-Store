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
        <p>Trạng thái: {{ $order->status }}</p>
    @endisset
    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
</body>
</html>
