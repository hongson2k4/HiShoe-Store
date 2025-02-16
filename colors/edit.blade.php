@extends('admin.layout.main')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4>Chỉnh Sửa Màu</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('colors.update', $color->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Tên Màu</label>
                    <input type="text" name="name" class="form-control" value="{{ $color->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mã Màu</label>
                    <input type="color" name="code" class="form-control form-control-color" value="{{ $color->code }}" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('colors.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection