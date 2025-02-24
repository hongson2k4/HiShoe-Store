@extends('admin.layout.main')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Thêm Màu Mới</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('colors.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên Màu</label>
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên màu" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mã Màu</label>
                    <input type="color" name="code" class="form-control form-control-color" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('colors.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-success">Lưu Màu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
