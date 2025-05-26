@extends('admin.layout.main')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Quản lý đơn hàng</h2>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('orders.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Nhập tên khách hàng"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="order_check" class="form-control" placeholder="Nhập mã đơn hàng"
                                value="{{ request('order_check') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                @foreach(\App\Models\Order::getStatusList() as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i> Lọc
                            </button>
                        </div>
                        <div class="col-md-3 text-end m-2 mt-3">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-1"></i> Bộ lọc mặc định
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>STT</th>
                                <th>Mã đơn hàng</th>
                                <th>Tên khách hàng</th>
                                <th>Trị giá đơn hàng</th>
                                <th>Phương thức t.toán</th>
                                <th>Trạng thái</th>
                                <th>Lý do hủy</th>
                                <th>Ngày tạo đơn</th>
                                <th>Xem đơn hàng</th>
                                <th>Hành động</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $key => $order)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $order->order_check }}</td>
                                    <td>{{ $order->user->full_name }}</td>
                                    <td>{{ number_format($order->total_price) }} VND</td>
                                    <td>
                                        @if($order->payment)
                                            {{ $order->payment->payment_method === 'cod' ? 'COD' : 'VNPAY' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="order-status-badge">
                                            <span class="badge text-light {{ $order->getStatusBadgeClass() }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($order->status == 5 && $order->customer_reasons)
                                            {{ $order->customer_reasons }}
                                        @elseif ($order->status == 5)
                                            Khác
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                            Chi tiết sản phẩm
                                        </a>
                                    </td>
                                    <td>
                                        @if ($order->needs_refunded)
                                            <span class="badge bg-danger text-white">Yêu cầu trả hàng</span>
                                            <form action="{{ route('orders.resolve-refunded', $order->id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Bạn đã xử lý yêu cầu trả hàng này?');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Xử lý trả hàng</button>
                                            </form>
                                        @elseif ($order->needs_support)
                                            <span class="badge bg-warning text-dark">Cần hỗ trợ</span>
                                            <form action="{{ route('orders.resolve-support', $order->id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Bạn đã xử lý yêu cầu hỗ trợ này?');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Xử lý hỗ trợ</button>
                                            </form>
                                        @elseif ($order->status == 1)
                                            <form action="{{ route('orders.confirm', $order->id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Xác nhận đơn hàng</button>
                                            </form>
                                        @elseif ($order->status == 2 || $order->status == 3)
                                            <span class="text-muted">Đang xử lý</span>
                                        @elseif ($order->status == 7)
                                            <span class="text-success">Khách đã nhận hàng</span>
                                        @elseif ($order->status == 0 || $order->status == 5 || $order->status == 6)
                                            <form action="{{ route('orders.delete', $order->id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Xóa đơn hàng</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Không có hành động</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Không có đơn hàng !
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($orders->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $orders->firstItem() }} đến {{ $orders->lastItem() }}
                            trong số {{ $orders->total() }} mục
                        </div>
                        <div>
                            {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection