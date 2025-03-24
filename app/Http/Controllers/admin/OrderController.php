<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // public function index(Request $request)
    // {
    //     $orders = Order::with(['user', 'orderDetails']) // eager load relationships
    //         ->when($request->filled('status'), function ($query) use ($request) {
    //             $query->where('status', $request->status);
    //         })
    //         ->paginate(10); // Use paginate instead of get()
    //     return view('admin.orders.index', compact('orders'));
    // }

    //Cập nhập index cho trạng thái hủy đơn hàng
    public function index(Request $request)
    {
        // Lấy dữ liệu tìm kiếm và lọc
        $search = $request->query('search');
        $status = $request->query('status');

        // Query đơn hàng
        $query = Order::with('user', 'product');

        // Tìm kiếm theo tên khách hàng
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // Phân trang
        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function resolveSupport($id)
    {
        try {
            // Tìm đơn hàng
            $order = Order::findOrFail($id);

            // Đặt lại trạng thái cần hỗ trợ
            $order->needs_support = 0;
            $order->save();

            // Chuyển hướng về trang danh sách đơn hàng với thông báo
            return redirect()->back()->with('success', 'Yêu cầu hỗ trợ đã được xử lý.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý yêu cầu hỗ trợ: ' . $e->getMessage());
        }
    }

    // Hiển thị chi tiết đơn hàng
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validatedStatus = $request->validate([
            'status' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($order) {
                    if (!$order->getAllowedNextStatuses($value)) {
                        $fail('Invalid status change.');
                    }
                }
            ]
        ]);

        $order->update(['status' => $validatedStatus['status']]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function confirm(Order $order)
    {
        // Kiểm tra trạng thái hiện tại
        if ($order->status != 1) {
            return redirect()->back()->with('error', 'Đơn hàng không thể xác nhận! Trạng thái không hợp lệ.');
        }

        // Cập nhật trạng thái sang "Đang đóng gói" (status = 2)
        $order->status = 2;
        $order->updated_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Đơn hàng đã được xác nhận thành công!');
    }

    // Tính năng xóa đơn hàng khi trạng thái đơn là đã hủy và đã trả hàng
    public function delete(Order $order)
    {
        // Kiểm tra trạng thái đơn hàng
        if ($order->status != 5 && $order->status != 6) {
            return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy hoặc đã trả hàng!');
        }

        // Xóa đơn hàng
        $order->delete();

        return redirect()->back()->with('success', 'Đơn hàng đã được xóa thành công!');
    }

}
