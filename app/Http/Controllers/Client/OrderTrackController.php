<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderCheck;
use App\Models\Users;

class OrderTrackController extends Controller {
    public function form() 
    {
        return view('client.order.form'); // Đường dẫn chính xác đến file view
    }
    // public function track(Request $request) {
    //     $order = OrderCheck::where('order_check', $request->order_check  )->first();
    //     if (!$order) {
    //         return redirect()->back()->with('error', 'Mã đơn hàng không tồn tại!');
    //     }
    //     return view('client.order.form', compact('order'));
    // }
    public function track(Request $request)
    {
        $order = OrderCheck::where('order_check', $request->order_check)->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Định nghĩa trạng thái đơn hàng
        $statusText = [
            1 => 'Chờ xử lý',
            2 => 'Đang đóng gói',
            3 => 'Đang vận chuyển',
            4 => 'Đã giao hàng',
            5 => 'Đã hủy',
            6 => 'Đã trả hàng',
            7 => 'Đã nhận hàng',
        ];

        $statusColor = [
            1 => '#ff9800', // Màu cam
            2 => '#2196f3', // Xanh dương
            3 => '#ffc107', // Vàng
            4 => '#4caf50', // Xanh lá
            5 => '#f44336', // Đỏ
            6 => '#9e9e9e',  // Xám
            7 => '#4caf50'  // xanh lá
        ];

        return view('client.order.form', compact('order', 'statusText', 'statusColor'));
    }

    public function showOrderDetails($orderId)
    {
        $order = OrderCheck::with('users')->find($orderId);
    
        if (!$order) {
            return redirect()->route('order.form')->with('error', 'Không tìm thấy đơn hàng.');
        }
    
        return view('form', compact('order'));
    }

}


?>