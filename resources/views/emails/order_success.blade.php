{{-- filepath: resources/views/emails/order_success.blade.php --}}
<h2>Chào {{ $order->user->full_name ?? 'bạn' }},</h2>
<p>Bạn đã đặt hàng thành công tại HiShoe Store!</p>
<p>Mã đơn hàng: <strong>{{ $order->id }}</strong></p>
<ul>
    @foreach($cartItems as $item)
        <li>{{ $item->productVariant->product->name ?? '' }} - SL: {{ $item->quantity }}</li>
    @endforeach
</ul>
<p>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</p>
<p>Cảm ơn bạn đã mua sắm!</p>