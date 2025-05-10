@extends('admin.layout.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Chỉnh Sửa Thương Hiệu</h2>
            <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách Thương Hiệu
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('brands.update', $brand) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên Thương Hiệu <span class="text-danger">*</span></label>
                        <input type="text" name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $brand->name) }}" 
                               required 
                               maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <div class="form-control bg-light">
                            <span class="badge {{ $brand->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $brand->status ? 'Hoạt Động' : 'Không Hoạt Động' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mô Tả</label>
                    <textarea name="description" 
                              class="form-control" 
                              maxlength="1000" 
                              rows="4">{{ old('description', $brand->description) }}</textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập Nhật Thương Hiệu
                    </button>
                    @if($brand->product_count > 0)
                    <a href="{{ route('brands.products', $brand) }}" class="btn btn-info">
                        <i class="fas fa-list"></i> Xem Sản Phẩm ({{ $brand->product_count }})
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection