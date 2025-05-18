@extends('admin.layout.main')
@section('content')
<form action="{{ route('vouchers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label">Mã giảm giá</label>
        <input class="form-control @error('code') is-invalid @enderror" type="text" name="code" value="{{old('name')}}">
        @error('code')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Loại giảm giá</label>

        <select name="discount_type" id="" class="form-control">
            <option value="1">Giảm giá cố định</option>
            <option value="0">Giảm giá theo %</option>
        </select>
        @error('discount_type')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Giảm giá</label>
        <input class="form-control @error('discount_value') is-invalid @enderror" type="number" name="discount_value" value="{{old('name')}}">
        @error('discount_value')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Số tiền tối thiểu</label>
        <input class="form-control @error('min_order_value') is-invalid @enderror" type="number" name="min_order_value" value="{{old('name')}}">
        @error('min_order_value')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Số tiền tối đa</label>
        <input class="form-control @error('max_discount_value') is-invalid @enderror" type="number" name="max_discount_value" value="{{old('name')}}">
        @error('max_discount_value')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>


    <div class="mb-3">
        <label class="form-label">Ngày bắt đầu</label>
        <input class="form-control @error('end_date') is-invalid @enderror" type="date"  name="start_date" value="{{old('start_date')}}">
        @error('start_date')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Ngày kết thúc</label>
        <input class="form-control @error('end_date') is-invalid @enderror" type="date"  name="end_date" value="{{old('end_date')}}">
        @error('end_date')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Số lượng mã</label>
        <input class="form-control @error('usage_limit') is-invalid @enderror" type="number"  name="usage_limit" value="{{old('usage_limit')}}">
        @error('usage_limit')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>




    <button type="submit" class="btn btn-success">Thêm mới</button>
</form>


@endsection
