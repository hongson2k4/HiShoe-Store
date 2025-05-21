@extends('admin.layout.main')
@section('title', 'Danh sách mã giảm giá')
@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h2 class="mb-0">Quản lý mã giảm giá</h2>
                <div>
                    <a class="btn btn-success" href="{{ route('vouchers.create') }}">
                        <i class="fas fa-plus-circle me-1"></i> Thêm mới mã giảm giá
                    </a>
                    <form action="{{ route('vouchers.deleteExpired') }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Bạn có chắc muốn xóa tất cả mã đã hết hạn?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i> Xóa mã hết hạn
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                {{-- Hiển thị thông báo --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- Search Form --}}
                <form action="{{ route('vouchers.list') }}" method="GET" class="mb-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm mã giảm giá"
                                value="{{ request()->query('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Vouchers Table --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>STT</th>
                                <th>Mã</th>
                                <th>Loại giảm giá</th>
                                <th>Giá trị</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vouchers as $key => $u)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $u->code }}</td>
                                    <td>
                                        @if ($u->discount_type == 1)
                                            Giảm giá cố định
                                        @else
                                            Giảm giá theo %
                                        @endif
                                    </td>
                                    <td>
                                        @if ($u->discount_type == 1)
                                            {{ number_format($u->discount_value, 0, '', ',') }} VNĐ
                                        @else
                                            {{ $u->discount_value }} %
                                        @endif
                                    </td>
                                    <td>{{ $u->start_date }}</td>
                                    <td>{{ $u->end_date }}</td>
                                    <td>
                                        @if ($u->status == \App\Models\Voucher::STATUS_EXPIRED)
                                            <span class="badge bg-secondary text-light">Hết hạn</span>
                                        @elseif ($u->status == \App\Models\Voucher::STATUS_ACTIVE)
                                            <span class="badge bg-success text-dark">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger text-light">Tạm đóng</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('vouchers.edit', $u->id) }}" class="btn btn-sm btn-warning me-1"
                                                title="Chỉnh sửa mã giảm giá">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('vouchers.delete', $u->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Xác nhận xóa?')" title="Xóa mã giảm giá">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Không tìm thấy mã giảm giá. Hãy tạo mã giảm giá đầu tiên!
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    setTimeout(function() {
        $('.alert-dismissible').alert('close');
    }, 3000);
</script>
@endpush