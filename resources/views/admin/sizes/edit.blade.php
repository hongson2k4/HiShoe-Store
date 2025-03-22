@extends('admin.layout.main')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4>Chỉnh Sửa Size</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('sizes.update', $size->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Size</label>
                    <input type="text" name="name" class="form-control" value="{{ $size->name }}" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('sizes.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection