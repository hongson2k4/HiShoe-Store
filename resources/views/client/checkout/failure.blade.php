@extends('client.layout.main')
@section('title', 'Thanh toán thất bại')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto p-6 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md w-full">
        <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <h2 class="text-2xl font-bold text-red-600 mt-4">Thanh toán thất bại!</h2>
        <p class="text-gray-600 mt-2">{{ $message ?? 'Có lỗi xảy ra khi thanh toán. Đơn hàng chưa được tạo.' }}</p>
        <div class="mt-6">
            <a href="{{ route('checkout.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Thử lại thanh toán</a>
            <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Về trang chủ</a>
        </div>
        @if(!empty($cartItems) && count($cartItems))
        <div class="mt-8 text-left">
            <h3 class="font-semibold mb-2">Giỏ hàng của bạn:</h3>
            <ul>
                @foreach($cartItems as $item)
                    <li class="mb-2 border-b pb-2">
                        <span class="font-bold">{{ $item->product->name ?? '' }}</span>
                        <span class="ml-2">x{{ $item->quantity }}</span>
                        <span class="ml-2 text-green-600">{{ number_format($item->productVariant->price) }} VNĐ</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-2 font-bold">Tổng: <span class="text-lg text-blue-600">{{ number_format($subtotal) }} VNĐ</span></div>
        </div>
        @endif
    </div>
</div>
@endsection
