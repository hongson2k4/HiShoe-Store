@extends('client.layout.main')

@section('content')
<div class="container" style="margin-top: 100px;">
    <h1 class="text-center mb-5">Lịch sử đơn hàng</h1>

    <form action="{{ route('order-history') }}" method="GET" class="filter-form mb-3">
        <input type="text" name="order_id" placeholder="Mã đơn hàng">
        <input type="text" name="day" placeholder="Ngày">
        <input type="text" name="month" placeholder="Tháng">
        <input type="text" name="year" placeholder="Năm">
        <select name="status">
            <option value="">Chọn trạng thái</option>
            <option value="1">Đơn đã đặt</option>
            <option value="2">Đang đóng gói</option>
            <option value="3">Đang vận chuyển</option>
            <option value="4">Đã giao hàng</option>
            <option value="5">Đã hủy</option>
            <option value="6">Đã trả hàng</option>
        </select>
        <button type="submit">Lọc</button>
    </form>    

    @if ($orders->isEmpty())
        <p>Bạn chưa có đơn hàng nào.</p>
    @else
        <table class="table text-center mb-3" id="orderTable">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Xem đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->order_check }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
                        {{-- hiển thị bage màu sắc --}}
                        <td>
                            <span class="badge {{ $order->getStatusClass() }} text-white p-2 fs-6 rounded-pill fw-normal">
                                {{ $order->status_text }}
                            </span>
                        </td>                                                                                         
                        <td>
                            <a href="{{ route('order.history.detail', $order->id) }}"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <div class="mt-3">
        <a href="{{ route('home') }}" class="btn btn-success">Back to Home</a>
    </div>
</div>
@endsection

{{-- Phần javascript để sắp xếp tăng dần, giảm dần --}}
@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            if ($.fn.DataTable) {
                console.log("DataTables đã được load!");
            } else {
                console.log("Lỗi: DataTables chưa được tải.");
            }

            $('#orderTable').DataTable({
                "order": [[0, "desc"]], // Sắp xếp mặc định theo Mã đơn hàng giảm dần
                "columnDefs": [
                    { "orderable": true, "targets": [0, 1, 2] }, // Chỉ bật sắp xếp cho Mã đơn hàng, Ngày đặt, Tổng tiền
                    { "orderable": false, "targets": [3, 4] } // Tắt sắp xếp cho Trạng thái & Xem đơn hàng
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

