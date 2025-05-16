@extends('admin.layout.main')
@section('content')
<form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Mã giảm giá</label>
        <input class="form-control @error('code') is-invalid @enderror" type="text" name="code" value="{{ old('code', $voucher->code) }}">
        @error('code')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Loại giảm giá</label>
        <select name="discount_type" class="form-control">
            <option value="1" {{ $voucher->discount_type == 1 ? 'selected' : '' }}>Giảm giá cố định</option>
            <option value="0" {{ $voucher->discount_type == 0 ? 'selected' : '' }}>Giảm giá theo %</option>
        </select>
        @error('discount_type')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Giảm giá</label>
        <input class="form-control @error('discount_value') is-invalid @enderror" type="number" name="discount_value" value="{{ old('discount_value', $voucher->discount_value) }}">
        @error('discount_value')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Số tiền tối thiểu</label>
        <input class="form-control @error('min_order_value') is-invalid @enderror" type="number" name="min_order_value" value="{{ old('min_order_value', $voucher->min_order_value) }}">
        @error('min_order_value')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Số tiền tối đa</label>
        <input class="form-control @error('max_discount_value') is-invalid @enderror" type="number" name="max_discount_value" value="{{ old('max_discount_value', $voucher->max_discount_value) }}">
        @error('max_discount_value')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Ngày bắt đầu</label>
        <input class="form-control @error('start_date') is-invalid @enderror" type="date" name="start_date" value="{{ old('start_date', $voucher->start_date) }}">
        @error('start_date')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Ngày kết thúc</label>
        <input class="form-control @error('end_date') is-invalid @enderror" type="date" name="end_date" value="{{ old('end_date', $voucher->end_date) }}">
        @error('end_date')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Số lượng mã</label>
        <input class="form-control @error('usage_limit') is-invalid @enderror" type="number" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}">
        @error('usage_limit')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Trạng thái</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch"
                value="1" {{ $voucher->status == 1 ? 'checked' : '' }}>
            <label class="form-check-label" for="statusSwitch">Kích hoạt</label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>
@endsection
