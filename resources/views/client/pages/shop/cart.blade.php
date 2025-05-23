@extends('client.layout.main')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4">🛒 Giỏ hàng của bạn</h1>

    @if($cartItems->isEmpty())
        <div class="alert alert-warning text-center">
            Giỏ hàng của bạn đang trống.
            <a href="{{ route('shop') }}" class="alert-link">Bắt đầu mua sắm!</a>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body py-3 px-3">
                <form id="cart-form" method="POST">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check d-flex align-items-center">
                            <input class="form-check-input" type="checkbox" id="select-all" style="cursor: pointer;">
                            <label class="form-check-label ms-2 mb-0" for="select-all" style="user-select: none; cursor: pointer;">
                                Chọn tất cả
                            </label>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" id="delete-selected" style="white-space: nowrap;">
                            <i class="bi bi-trash-fill me-1"></i> Xóa sản phẩm đã chọn
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle text-center table-hover table-bordered mb-0" style="table-layout: fixed;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">&nbsp;</th>
                                    <th style="width: 35%; text-align: left;">Sản phẩm</th>
                                    <th style="width: 15%;">Số lượng</th>
                                    <th style="width: 15%;">Giá</th>
                                    <th style="width: 15%;">Tổng</th>
                                    <th style="width: 10%;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                                @foreach($cartItems as $item)
                                    @php
                                        $itemTotal = $item->variant->price * $item->quantity;
                                        $grandTotal += $itemTotal;
                                    @endphp
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <input type="checkbox" class="item-checkbox form-check-input" data-id="{{ $item->id }}" data-total="{{ $itemTotal }}" checked>
                                        </td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ Storage::url($item->product->image_url) }}" alt="{{ $item->product->name }}" width="60" height="60" class="rounded" style="object-fit: cover;">
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong><br>
                                                    <small class="text-muted">Màu: {{ $item->variant->color->name }} | Size: {{ $item->variant->size->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm justify-content-center" style="max-width: 110px;">
                                                <button class="btn btn-outline-secondary btn-qty-change" data-action="decrease" data-id="{{ $item->id }}" type="button" style="padding: 0 8px;">−</button>
                                                <input type="text" class="form-control text-center item-qty" value="{{ $item->quantity }}" readonly data-id="{{ $item->id }}" style="max-width: 40px;">
                                                <button class="btn btn-outline-secondary btn-qty-change" data-action="increase" data-id="{{ $item->id }}" type="button" style="padding: 0 8px;">+</button>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->variant->price, 0, ',', '.') }} VNĐ</td>
                                        <td class="item-total">{{ number_format($itemTotal, 0, ',', '.') }} VNĐ</td>
                                        <td>
                                            <form action="{{ route('cart.delete', $item->id) }}" method="POST" class="delete-single-form d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa sản phẩm" style="padding: 4px 8px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                    <a href="{{ route('shop') }}" class="btn btn-outline-primary btn-sm">← Tiếp tục mua sắm</a>
                    <div class="text-end" style="min-width: 220px;">
                        <h5 class="mb-1">
                            Tổng cộng:
                            <strong class="grand-total">{{ number_format($grandTotal, 0, ',', '.') }} VNĐ</strong>
                        </h5>
                        <button type="button" class="btn btn-success btn-sm w-100 proceed-checkout">
                            Tiến hành thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    // Cập nhật tổng tiền
    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            total += parseInt(cb.getAttribute('data-total'));
        });
        document.querySelector('.grand-total').textContent = total.toLocaleString('vi-VN') + ' VNĐ';
    }

    // Chọn tất cả
    const selectAllCheckbox = document.getElementById('select-all');
    selectAllCheckbox.addEventListener('change', function () {
        const checked = this.checked;
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = checked);
        updateGrandTotal();
    });

    // Chọn từng sản phẩm
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else {
                selectAllCheckbox.checked = Array.from(document.querySelectorAll('.item-checkbox')).every(cb => cb.checked);
            }
            updateGrandTotal();
        });
    });

    // Tăng giảm số lượng
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
                    const input = document.querySelector(`.item-qty[data-id="${itemId}"]`);
                    input.value = data.item.quantity;

                    const row = input.closest('tr');
                    const itemTotal = data.item.quantity * data.item.price;
                    row.querySelector('.item-total').textContent = `${itemTotal.toLocaleString('vi-VN')} VND`;

                    row.querySelector('.item-checkbox').setAttribute('data-total', itemTotal);

                    updateGrandTotal();
                } else {
                    alert(data.message || 'Đã xảy ra lỗi. Số lượng quá chỉ định.');
                }
            })
            .catch(() => alert('Lỗi khi gửi yêu cầu đến máy chủ.'));
        });
    });

    updateGrandTotal();

    // Ngăn checkout nếu chưa chọn sản phẩm
    document.querySelector('.proceed-checkout').addEventListener('click', function (e) {
        const checked = Array.from(document.querySelectorAll('.item-checkbox:checked'));
        if (checked.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
            return;
        }

        // Lấy danh sách id sản phẩm đã chọn
        const ids = checked.map(cb => cb.dataset.id).join(',');
        // Điều hướng sang trang checkout với danh sách id
        window.location.href = `{{ route('checkout.index') }}?cart_ids=${ids}`;
    });

    // Xóa các sản phẩm đã chọn (AJAX, không reload từng form)
    document.getElementById('delete-selected').addEventListener('click', function () {
    const checkedBoxes = Array.from(document.querySelectorAll('.item-checkbox:checked'));
    if (checkedBoxes.length === 0) {
        alert('Vui lòng chọn sản phẩm muốn xóa.');
        return;
    }
    if (!confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) return;

    // Lấy danh sách id
    const ids = checkedBoxes.map(cb => cb.dataset.id);

    // Gửi AJAX xoá từng sản phẩm, sau đó reload lại trang
    Promise.all(ids.map(id =>
        fetch(`/cart/delete/${id}`, { 
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
    )).then(() => location.reload());
});
});
</script>
@endsection
