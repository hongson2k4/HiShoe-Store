@extends('admin.layout.main')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Thêm Size Mới</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('sizes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Kích thước</label>
                    <input type="text" name="name" class="form-control" placeholder="Nhập size giày" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('sizes.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection