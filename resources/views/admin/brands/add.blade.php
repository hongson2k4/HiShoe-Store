@extends('admin.layout.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Tạo Thương Hiệu Mới</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('brands.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Tên Thương Hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           required 
                           maxlength="255"
                           placeholder="Nhập tên thương hiệu">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mô Tả</label>
                    <textarea name="description" 
                              class="form-control" 
                              maxlength="1000" 
                              rows="4"
                              placeholder="Mô tả thương hiệu (không bắt buộc)">{{ old('description') }}</textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        Tạo Thương Hiệu
                    </button>
                    <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                        Quay Lại Danh Sách
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection