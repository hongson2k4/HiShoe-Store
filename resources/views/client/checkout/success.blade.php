@extends('client.layout.main')
@section('title')
HiShoe-Store - Thanh toán thành công
@endsection
@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<body class="bg-gray-100">
    <div id="header"></div>

    <div class="container mx-auto p-6 flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md">
            <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
            <h2 class="text-2xl font-bold text-green-600 mt-4">Thanh toán thành công!</h2>
            <p class="text-gray-600 mt-2">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận.</p>

            <div class="bg-gray-100 p-4 rounded mt-4 text-left">
                <p class="font-semibold">Mã đơn hàng: <span class="text-red-500">{{ $order->id }}</span></p>
                <p>Số tiền thanh toán: <span class="font-bold text-lg text-green-600">{{ number_format($order->total_price) }} VNĐ</span></p>
                @if ($payment)
                    <p>Phương thức thanh toán:
                        <span class="font-semibold">
                            @if ($payment->payment_method == 'cod')
                                Thanh toán khi nhận hàng (COD)
                            @elseif ($payment->payment_method == 'bank-transfer')
                                Chuyển khoản ngân hàng
                            @elseif ($payment->payment_method == 'vnpay')
                                Thanh toán qua VNPAY
                            @else
                                Không xác định
                            @endif
                        </span>
                    </p>
                @else
                    <p>Phương thức thanh toán: <span class="font-semibold">Không có thông tin thanh toán</span></p>
                @endif
            </div>

            <div class="mt-6 flex flex-col gap-3">
                <a href="{{ route('home') }}" class="w-full bg-blue-500 text-white px-4 py-2 rounded text-center">Quay lại trang chủ</a>
                <a href="#" class="w-full bg-gray-500 text-white px-4 py-2 rounded text-center">Xem đơn hàng của bạn</a>
            </div>
        </div>
    </div>

    <div id="footer"></div>

    <script>
        document.getElementById("header").innerHTML = `<include src='header.html'></include>`;
        document.getElementById("footer").innerHTML = `<include src='footer.html'></include>`;
    </script>
</body>
@endsection
