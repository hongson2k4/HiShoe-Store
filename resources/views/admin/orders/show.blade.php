@extends('admin.layout.main')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Order Details #{{ $order->id }}</h2>
            <div class="order-status-badge">
                <span class="badge text-light {{ $order->getStatusBadgeClass() }}">
                    {{ $order->status_text }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Customer Information</h4>
                    <p><strong>Name:</strong> {{ $order->user->name ?? 'Unknown' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                </div>
                <div class="col-md-6">
                    <h4>Order Information</h4>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
                    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                </div>
            </div>

            <h4 class="mt-4">Order Items</h4>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->getProductName() }}</td>
                        <td>{{ $detail->getVariantDetails() }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>${{ number_format($detail->price, 2) }}</td>
                        <td>${{ number_format($detail->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
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
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
            <a href="{{ route('orders.index') }}" class="btn btn-secondary m-2">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection