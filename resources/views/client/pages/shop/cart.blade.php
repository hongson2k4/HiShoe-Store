@extends('client.layout.main')

@section('content')
    <div class="container my-5">
        <h1 class="text-center mb-4">Giỏ hàng của bạn</h1>

        @if($cartItems->isEmpty())
            <div class="alert alert-warning text-center" role="alert">
                Giỏ hàng của bạn đang trống. <a href="{{ route('shop') }}" class="alert-link">Bắt đầu mua sắm!</a>
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Màu</th>
                                    <th>Kích thước</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                                @foreach($cartItems as $item)
                                    @php $itemTotal = $item->variant->price * $item->quantity;
                                    $grandTotal += $itemTotal; @endphp
                                    <tr class="text-center">
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->variant->color->name }}</td>
                                        <td>{{ $item->variant->size->name }}</td>
                                        <td>
                                            <div class="input-group input-group-sm justify-content-center">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-secondary btn-qty-change" data-action="decrease"
                                                        data-id="{{ $item->id }}">−</button>
                                                </div>
                                                <input type="text" class="form-control text-center item-qty"
                                                    value="{{ $item->quantity }}" readonly style="max-width: 50px;"
                                                    data-id="{{ $item->id }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary btn-qty-change" data-action="increase"
                                                        data-id="{{ $item->id }}">+</button>
                                                </div>
                                            </div>
                                        </td>

                                        <td>${{ number_format($item->variant->price, 2) }}</td>
                                        <td>${{ number_format($itemTotal, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.delete', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Xác nhận xóa sản phẩm này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                        <a href="{{ route('shop') }}" class="btn btn-outline-primary mb-2">← Tiếp tục mua sắm</a>
                        <div class="text-right">
                            <h5 class="mb-3">Tổng cộng: <strong>${{ number_format($grandTotal, 2) }}</strong></h5>
                            <a href="{{ route('checkout.index') }}" class="btn btn-success">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    document.querySelectorAll('.btn-qty-change').forEach(button => {
        button.addEventListener('click', function () {
            const itemId = this.dataset.id;
            const action = this.dataset.action;
            const url = `/api/cart/${itemId}`;
            
            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ action })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật số lượng và tổng
                    const input = document.querySelector(`.item-qty[data-id="${itemId}"]`);
                    input.value = data.item.quantity;

                    const row = input.closest('tr');
                    row.querySelector('td:nth-child(6)').textContent = `$${(data.item.quantity * data.item.price).toFixed(2)}`;
                    document.querySelector('h5 strong').textContent = `$${data.grandTotal.toFixed(2)}`;
                } else {
                    alert(data.message || 'Đã xảy ra lỗi.');
                }
            })
            .catch(() => alert('Lỗi khi gửi yêu cầu đến máy chủ.'));
        });
    });
});
</script>


@endsection