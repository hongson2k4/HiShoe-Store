@extends('client.layout.main')

@section('content')
<div class="container" style="margin-top: 100px;">
    <!-- Thêm thông báo -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="text-center mb-5">Lịch sử đơn hàng</h1>
    <!-- Phần còn lại của code giữ nguyên -->
    

    <form action="{{ route('order-history') }}" method="GET" class="filter-form mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Nhập mã đơn hàng">
                    <label for="order_id">Mã đơn hàng</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="day" name="day" placeholder="Nhập ngày">
                    <label for="day">Ngày</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="month" name="month" placeholder="Nhập tháng">
                    <label for="month">Tháng</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" id="year" name="year" placeholder="Nhập năm">
                    <label for="year">Năm</label>
                </div>
            </div>
    
            <div class="col">
                <div class="form-floating">
                    <select class="form-select" id="status" name="status">
                        <option value="" selected>Chọn trạng thái</option>
                        <option value="1">Đơn đã đặt</option>
                        <option value="2">Đang đóng gói</option>
                        <option value="3">Đang vận chuyển</option>
                        <option value="4">Đã giao hàng</option>
                        <option value="5">Đã hủy</option>
                        <option value="6">Đã trả hàng</option>
                        <option value="7">Đã Nhận hàng</option>
                    </select>
                    <label for="status">Trạng thái</label>
                </div>
            </div>
    
            <div class="col-auto">
                <button type="submit" class="btn text-white" style="padding: 16px; background-color: #F89CAB;">Lọc</button>
            </div>
        </div>
    </form>

    @if ($orders->isEmpty())
        <p>Bạn chưa có đơn hàng nào.</p>
    @else
        <table class="table text-center mb-3" id="orderTable">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Xem đơn hàng</th>
                    <th>Hành động</th> <!-- Thêm cột Hành động -->
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->product ? $order->product->name : 'Không có sản phẩm' }}</td> <!-- Hiển thị tên sản phẩm -->
                        <td>{{ $order->order_check }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
                        <td>
                            <span class="badge {{ $order->getStatusClass() }} text-white p-2 fs-6 rounded-pill fw-normal">
                                {{ $order->status_text }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('order.history.detail', $order->id) }}"><i class="fa-regular fa-eye"></i></a>
                        </td>
                        {{-- tính năng các nút hủy đơn hàng, liên hệ, đã nhận được hàng, trả hàng/ hoàn tiền. --}}
                        <td>
                            @if ($order->canCancel())
                                <form action="{{ route('order.history.cancel', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Hủy đơn hàng</button>
                                </form>
                            @elseif ($order->status == 1 && $order->isOver7Days())
                                @if ($order->needs_support)
                                    <span class="text-muted">Shop sẽ liên lạc cho bạn! trong thời gian sớm nhất!!</span>
                                @else
                                    <button type="button" class="btn btn-primary btn-sm contact-shop-btn" data-order-id="{{ $order->id }}">Liên hệ shop</button>
                                @endif
                            @elseif ($order->status == 2 || $order->status == 3)
                                @if ($order->needs_support)
                                    <span class="text-muted">Shop sẽ liên lạc cho bạn! trong thời gian sớm nhất!!</span>
                                @else
                                    <button type="button" class="btn btn-primary btn-sm contact-shop-btn" data-order-id="{{ $order->id }}">Liên hệ shop</button>
                                @endif
                            @elseif ($order->status == 4)
                                <form action="{{ route('order.history.receive', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn đã nhận được hàng?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Đã nhận được hàng</button>
                                </form>
                            @elseif ($order->status == 7)
                                <div class="d-flex gap-2">
                                    @if ($order->needs_refunded)
                                        <span class="text-warning">Đang xem xét</span>
                                    @elseif (($order->is_reviewed != 1) && ($order->is_refunded != 1))
                                        <form action="{{ route('order.history.review', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn muốn đánh giá sản phẩm?');">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm">Đánh giá sản phẩm</button>
                                        </form>
                                        <form action="{{ route('order.history.refund', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn yêu cầu trả hàng/hoàn tiền?');">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Trả hàng/Hoàn tiền</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('order.history.rebuy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có muốn mua lại đơn hàng này?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm btn-rebuy">Mua lại</button>
                                    </form>
                                </div>
                            @elseif ($order->status == 5 || $order->status == 6)
                                <form action="{{ route('order.history.rebuy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có muốn mua lại đơn hàng này?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm btn-rebuy">Mua lại</button>
                                </form>
                            @else
                                <span class="text-muted">Không thể hủy</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <div class="mt-3">
        <a href="{{ route('home') }}" class="btn text-white" style="background-color: #F89CAB;">Back to Home</a>
    </div>
</div>

{{-- Script xử lý liên hệ shop --}}
<script>
    document.querySelectorAll('.contact-shop-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            
            // Hiển thị alert xác nhận
            if (confirm('Bạn có muốn liên hệ với shop không?')) {
                // Gửi request AJAX
                fetch('{{ route('order.history.contact') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: orderId })
                })
                .then(response => {
                    // Kiểm tra nếu response không phải JSON
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Thay đổi nút thành thông báo
                        button.outerHTML = '<span class="text-muted">Shop sẽ liên lạc cho bạn! trong thời gian sớm nhất!!</span>';
                        // Hiển thị alert thông báo thành công
                        alert('Đã báo với admin');
                    } else {
                        // Hiển thị thông báo lỗi từ server
                        alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi gửi yêu cầu: ' + error.message);
                });
            }
        });
    });
</script>


@endsection

{{-- Phần javascript để sắp xếp tăng dần, giảm dần --}}
@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
       $(document).ready(function () {
            $('#orderTable').DataTable({
                "order": [[1, "desc"]], // Sắp xếp mặc định theo Mã đơn hàng (index 1)
                "columnDefs": [
                    { "orderable": false, "targets": [0, 4, 5, 6] }, // Tắt sắp xếp ở Tên sản phẩm, Trạng thái, Xem đơn hàng, Hành động
                    { "orderable": true, "targets": [1, 2, 3] } // Chỉ bật sắp xếp ở Mã đơn hàng, Ngày đặt, Tổng tiền
                ],
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ đơn hàng",
                    "zeroRecords": "Không tìm thấy đơn hàng nào",
                    "info": "Hiển thị _START_ - _END_ của _TOTAL_ đơn hàng",
                    "infoEmpty": "Không có dữ liệu",
                    "infoFiltered": "(lọc từ _MAX_ đơn hàng)",
                    "search": "Tìm kiếm:",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "»",
                        "previous": "«"
                    }
                }
            });
        });
    </script>
@endpush