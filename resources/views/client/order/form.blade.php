@extends('client.layout.main')

@section('content')
    <div class="container" style="margin-top: 100px;">
        <h2 class="text-center mb-5">Nhập mã đơn hàng để kiểm tra</h2>
        <form action="{{ route('order.track') }}" method="POST">
            @csrf
            <form action="{{ route('order.track') }}" method="POST" class="d-flex justify-content-center">
                @csrf
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" name="order_check" class="form-control" placeholder="Nhập mã đơn hàng">
                    <button class="btn text-white" style="background-color: #F89CAB;" type="submit">
                        <i class="bi bi-search"></i> Kiểm tra
                    </button>
                </div>
            </form>
            
        </form>
        <div class="m-3"></div>
        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

            @isset($order)
            {{-- <h3>Thông tin đơn hàng</h3> --}}
            <table class="table table-hover table-bordered">
                <tr>
                    <th style="width: 150px;">Mã đơn hàng:</th>
                    <td>{{ $order->order_check }}</td>
                </tr>
                <tr>
                    <th>Tên khách hàng:</th>
                    <td>{{ $order->user->full_name ?? 'Không xác định' }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ:</th>
                    <td>{{ $order->user->address ?? 'Không xác định' }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại:</th>
                    <td>{{ $order->user->phone_number ?? 'Không xác định' }}</td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td>
                        <span class="badge text-white"
                            style="background-color: {{ $statusColor[$order->status] ?? 'black' }};">
                            {{ $statusText[$order->status] ?? 'Không xác định' }}
                        </span> 
                    </td>
                    
                    
                </tr>
            </table>
            
            <div class="m-3"></div>

            {{-- Truyền $order vào status.blade.php --}}
            @include('client.order.status', ['order' => $order, 'statusText' => $statusText, 'statusColor' => $statusColor])

        @endisset
        <div class="m-3"></div>
        <div class="mt-3">
            <a href="{{ route('home') }}" class="btn text-white" style="background-color: #F89CAB;">Back to Home</a>
        </div>
    </div>

@endsection