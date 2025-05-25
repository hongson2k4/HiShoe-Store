@extends('client.layout.main')
@section('title', 'HiShoe-Store - Thanh toán')
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center">Thanh toán đơn hàng</h2>
    <div class="row g-4">
        <!-- Thông tin khách hàng -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3">Thông tin khách hàng</h5>
                <form id="payment-form" method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    <input type="hidden" name="cart_ids" value="{{ request('cart_ids') }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ và tên</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="first_name" class="form-control" placeholder="Họ" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="last_name" class="form-control" placeholder="Tên" value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ</label>
                        <input type="text" name="address" class="form-control" placeholder="Nhập địa chỉ" value="{{ old('address') }}">
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phương thức thanh toán</label>
                        <select name="payment_method" class="form-select">
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng (COD)</option>
                            <option value="vnpay" {{ old('payment_method') == 'vnpay' ? 'selected' : '' }}>Thanh toán qua VNPAY</option>
                        </select>
                        @error('payment_method')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="total" id="total-input" value="{{ $total }}">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cart') }}" class="text-secondary">Quay lại giỏ hàng</a>
                        <button type="submit" class="btn btn-dark py-2 px-4">Tiếp tục</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
                <div class="border rounded-3 p-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                    @foreach($cartItems as $item)
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ Storage::url($item->product->image_url) }}" alt="{{ $item->product->name }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                        <div class="d-flex justify-content-between w-100">
                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                            <span>{{ number_format($item->productVariant->price * $item->quantity) }} VNĐ</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mb-3">
    <label class="form-label fw-semibold">Mã giảm giá</label>
    <div class="input-group">
        <input type="text" name="voucher_code" class="form-control" placeholder="Nhập mã giảm giá" value="{{ old('voucher_code') }}" {{ session('applied_voucher_id') ? 'readonly' : '' }}>
        <button type="button" class="btn btn-dark" onclick="applyVoucher()" {{ session('applied_voucher_id') ? 'disabled' : '' }}>Áp dụng</button>
        <button type="button" class="btn btn-outline-danger" id="remove-voucher-btn" onclick="removeVoucher()" style="{{ session('applied_voucher_id') ? '' : 'display:none' }}">Hủy mã</button>
    </div>
    @error('voucher_code')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Tạm tính:</span>
                    <span class="fw-semibold" id="subtotal-text">{{ number_format($subtotal) }} VNĐ</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-success {{ $discount > 0 ? '' : 'd-none' }}" id="discount-row">
                    <span>Giảm giá:</span>
                    <span id="discount-text">{{ $discount > 0 ? '-'.number_format($discount).' VNĐ' : '' }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong class="fs-5">Tổng cộng:</strong>
                    <strong class="text-primary fs-5" id="total-text">{{ number_format($total) }} VNĐ</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function applyVoucher() {
    let voucherCode = document.querySelector('input[name="voucher_code"]').value;
    if (voucherCode) {
        fetch('{{ route("apply.voucher") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ voucher_code: voucherCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Áp dụng mã giảm giá thành công! Giảm: ' + data.discount + ' VNĐ');
                document.getElementById('subtotal-text').innerText = data.subtotal + ' VNĐ';
                document.getElementById('total-text').innerText = data.total + ' VNĐ';
                document.getElementById('total-input').value = data.total.replace(/[^0-9]/g, '');
                let discountRow = document.getElementById('discount-row');
                let discountText = document.getElementById('discount-text');
                if (parseInt(data.discount.replace(/,/g, '')) > 0) {
                    discountRow.classList.remove('d-none');
                    discountText.innerText = '-' + data.discount + ' VNĐ';
                } else {
                    discountRow.classList.add('d-none');
                    discountText.innerText = '';
                }
            
                document.querySelector('input[name="voucher_code"]').setAttribute('readonly', true);
                document.querySelector('button[onclick="applyVoucher()"]').setAttribute('disabled', true);
                document.getElementById('remove-voucher-btn').style.display = '';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
        });
    } else {
        alert('Vui lòng nhập mã giảm giá.');
    }
}

function removeVoucher() {
    fetch('{{ route("remove.voucher") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã hủy mã giảm giá.');
            document.getElementById('subtotal-text').innerText = data.subtotal + ' VNĐ';
            document.getElementById('total-text').innerText = data.total + ' VNĐ';
            document.getElementById('total-input').value = data.total.replace(/[^0-9]/g, '');
            let discountRow = document.getElementById('discount-row');
            let discountText = document.getElementById('discount-text');
            discountRow.classList.add('d-none');
            discountText.innerText = '';

            document.querySelector('input[name="voucher_code"]').removeAttribute('readonly');
            document.querySelector('button[onclick="applyVoucher()"]').removeAttribute('disabled');
            document.getElementById('remove-voucher-btn').style.display = 'none';
            document.querySelector('input[name="voucher_code"]').value = '';
        } else {
            alert('Có lỗi khi hủy mã giảm giá.');
        }
    })
    .catch(error => {
        alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
    });
}
</script>
@endsection
