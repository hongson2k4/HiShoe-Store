
@if(session('error'))
    <p>{{ session('error') }}</p>
@endif
<p>Mã đơn hàng: {{ $order->order_code }}</p>
<p>Trạng thái: {{ $order->status }}</p>
@if($order->status == 'delivered')
    <a href="#">Đánh giá sản phẩm</a>
@endif