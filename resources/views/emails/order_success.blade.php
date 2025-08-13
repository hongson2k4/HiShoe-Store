{{-- filepath: resources/views/emails/order_success.blade.php --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f0f0f0;
        }
        .header img {
            max-height: 60px;
        }
        .content {
            padding: 20px 0;
        }
        .order-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            font-size: 12px;
            color: #777;
        }
        .thank-you {
            text-align: center;
            margin: 30px 0;
            font-size: 18px;
            color: #4CAF50;
        }
        .cta-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>

    <div class="header">
        <img src="{{ asset('logo.png') }}" alt="Logo Công ty">
        <h1>Xác Nhận Đơn Hàng</h1>
    </div>
    
    <div class="content">
        <p>Kính gửi <strong>{{ $order->user_receiver }}</strong>,</p>
        
        <p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi. Đơn hàng của bạn đã được xác nhận và đang được xử lý.</p>
        
        <div class="order-info">
            <h2>Thông tin đơn hàng</h2>
            <p><strong>Mã đơn hàng:</strong> {{ $order->order_check }}</p>
            <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Phương thức thanh toán:</strong> 
                {{ $payment->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'VNPAY' }}
            </p>
        </div>
        
        <div class="customer-info">
            <h2>Thông tin người nhận</h2>
            <p><strong>Họ và tên:</strong> {{ $order->user_receiver }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone_receiver }}</p>
        </div>
        
        <h2>Chi tiết đơn hàng</h2>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Màu sắc</th>
                    <th>Kích thước</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->productVariant->product->name ?? '' }}</td>
                        <td>{{ $item->productVariant->color->name ?? '' }}</td>
                        <td>{{ $item->productVariant->size->name ?? '' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->productVariant->price) }} VNĐ</td>
                        <td>{{ number_format($item->productVariant->price * $item->quantity) }} VNĐ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total">
            <p>Tổng tiền hàng: {{ number_format($cartItems->sum(fn($i) => $i->productVariant->price * $i->quantity)) }} VNĐ</p>
            <p>Phí vận chuyển: 0 VNĐ</p>
            <p>Tổng thanh toán: {{ number_format($order->total_price) }} VNĐ</p>
        </div>
        
        <div class="thank-you">
            <p>Cảm ơn bạn đã mua sắm cùng chúng tôi!</p>
        </div>
        
        <a href="{{ url('/') }}" class="cta-button">Theo dõi đơn hàng</a>
        
        <p>Nếu bạn có bất kỳ câu hỏi nào về đơn hàng, vui lòng liên hệ với chúng tôi qua email {{ config('mail.from.address') }} hoặc số điện thoại [số điện thoại hỗ trợ].</p>
    </div>
    
    <div class="footer">
        <p>[Tên công ty] | [Địa chỉ công ty]</p>
        <p>[Số điện thoại] | {{ config('mail.from.address') }} | [Website]</p>
        <p>&copy; {{ date('Y') }} [Tên công ty]. Tất cả các quyền được bảo lưu.</p>
    </div>