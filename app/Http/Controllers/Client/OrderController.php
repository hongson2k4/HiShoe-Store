<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // API xác nhận thanh toán đơn hàng
    public function confirmPayment(Request $request) {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::find($request->order_id);
        if ($order->status != 0) { // Chỉ cho phép thanh toán nếu đơn hàng đang chờ xử lý
            return response()->json(['message' => 'Đơn hàng đang chờ xử lý '], 400);
        }

        $order->update(['status' => 5]); // Cập nhật trạng thái thành "Đã thanh toán"
        return response()->json(['message' => 'Đã thanh toán ', 'order' => $order]);
    }

    // API hủy đơn hàng
    public function cancelOrder(Request $request) {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::find($request->order_id);
        if ($order->status != 0) { // Chỉ cho phép hủy nếu đơn hàng đang chờ xử lý
            return response()->json(['message' => 'Đơn hàng không thể hủy'], 400);
        }

        $order->update(['status' => 4]); // Cập nhật trạng thái thành "Đã hủy"
        return response()->json(['message' => 'Đơn hàng đã được hủy ', 'order' => $order]);
    }
}
