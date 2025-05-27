@extends('admin.layout.main')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Quản lý nhãn hàng</h2>
            <a class="btn btn-primary" href="{{ route('brands.create') }}">
                <i class="fas fa-plus-circle me-1"></i> Thêm mới
            </a>
        </div>
        <div class="card-body">
            {{-- Form Tìm kiếm và Lọc --}}
            <form action="{{ route('brands.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Tìm theo tên nhãn hàng" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                Đang hoạt động
                            </option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                Không hoạt động
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh me-1"></i> Đặt lại bộ lọc
                        </a>
                    </div>
                </div>
            </form>

            {{-- Bảng danh sách nhãn hàng --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>Tên nhãn hàng</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $key => $brand)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ Str::limit($brand->description, 50) }}</td>
                            <td>
                                <span class="badge {{ $brand->status ? 'bg-success text-dark' : 'bg-secondary text-light' }}">
                                    {{ $brand->status ? 'Đang hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('brands.edit', $brand) }}" 
                                       class="btn btn-sm btn-warning me-1" 
                                       title="Chỉnh sửa nhãn hàng">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('brands.toggle', $brand) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                                class="btn btn-sm text-light {{ $brand->status ? 'btn-secondary text-danger' : 'btn-success ' }}"
                                                onclick="return confirm('{{ $brand->status ? 'Bạn có chắc muốn ngừng hoạt động nhãn hàng này?' : 'Bạn có chắc muốn kích hoạt nhãn hàng này?' }}')"
                                                title="{{ $brand->status ? 'Ngừng hoạt động' : 'Kích hoạt' }}">
                                            <i class="fas fa-{{ $brand->status ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Không có nhãn hàng nào được tìm thấy. Hãy thêm nhãn hàng đầu tiên!
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Hiển thị từ {{ $brands->firstItem() }} đến {{ $brands->lastItem() }} 
                    trên tổng số {{ $brands->total() }} nhãn hàng
                </div>
                <div>
                    {{ $brands->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
