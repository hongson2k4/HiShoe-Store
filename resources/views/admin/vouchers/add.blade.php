@extends('admin.layout.main')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thêm Mã Giảm Giá</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('vouchers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Mã giảm giá</label>
                            <input class="form-control @error('code') is-invalid @enderror" type="text" name="code" value="{{ old('code') }}" placeholder="Nhập mã giảm giá">
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Loại giảm giá</label>
                            <select name="discount_type" id="discount_type" class="form-control custom-select @error('discount_type') is-invalid @enderror">
                                <option value="1" {{ old('discount_type') == '1' ? 'selected' : '' }}>Giảm giá cố định</option>
                                <option value="0" {{ old('discount_type') == '0' ? 'selected' : '' }}>Giảm giá theo %</option>
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Giảm giá</label>
                            <div class="input-group">
                                <input class="form-control @error('discount_value') is-invalid @enderror" type="number" name="discount_value" value="{{ old('discount_value') }}" id="discount_value" placeholder="Nhập giá trị giảm giá">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="discount-unit">{{ old('discount_type') == '0' ? '%' : 'VND' }}</span>
                                </div>
                            </div>
                            @error('discount_value')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Số tiền tối thiểu</label>
                            <input class="form-control @error('min_order_value') is-invalid @enderror" type="number" name="min_order_value" value="{{ old('min_order_value') }}" placeholder="Nhập số tiền tối thiểu">
                            @error('min_order_value')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Số tiền tối đa</label>
                            <input class="form-control @error('max_discount_value') is-invalid @enderror" type="number" name="max_discount_value" value="{{ old('max_discount_value') }}" placeholder="Nhập số tiền tối đa">
                            @error('max_discount_value')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Ngày bắt đầu</label>
                            <input class="form-control @error('start_date') is-invalid @enderror" type="date" name="start_date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Ngày kết thúc</label>
                            <input class="form-control @error('end_date') is-invalid @enderror" type="date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Số lượng mã</label>
                            <input class="form-control @error('usage_limit') is-invalid @enderror" type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Nhập số lượng mã">
                            @error('usage_limit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success px-4">Thêm mới</button>
                            <a href="{{ route('vouchers.list') }}" class="btn btn-secondary px-4">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountType = document.getElementById('discount_type');
        const discountValue = document.getElementById('discount_value');
        const discountUnit = document.getElementById('discount-unit');

        if (discountType && discountValue && discountUnit) {
            function updateDiscountInput() {
                if (discountType.value === '0') {
                    discountUnit.textContent = '%';
                    discountValue.setAttribute('max', '60');
                    discountValue.setAttribute('min', '0');
                } else {
                    discountUnit.textContent = 'VND';
                    discountValue.removeAttribute('max');
                    discountValue.setAttribute('min', '0');
                }
            }

            discountType.addEventListener('change', updateDiscountInput);
            updateDiscountInput(); // Initialize on page load
        } else {
            console.error('One or more elements (discount_type, discount_value, discount-unit) not found.');
        }
    });
</script>
@endpush
@endsection