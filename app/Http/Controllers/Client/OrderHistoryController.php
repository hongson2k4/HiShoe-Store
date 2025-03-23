<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderHistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Chặn người chưa đăng nhập
    }

    public function index(Request $request)
    {
        //Hiện thông báo Đăng nhập để xem lịch sử đơn hàng!! khi chuyển trang form đăng nhập
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Đăng nhập để xem lịch sử đơn hàng!!');
        }

        // $query = Order::query(); Lấy tất cả dữ liệu từ bảng orders
        $query = Order::where('user_id', auth()->id()); // Lọc đơn hàng theo user đang đăng nhập

        if ($request->filled('order_id')) {
            $query->where('order_check', $request->order_id);
        }
        if ($request->filled('day')) {
            $query->whereDay('created_at', $request->day);
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return view('client.history.order-history', compact('orders'));
    }

    public function show($id)
        {
            $order = Order::with('orderItemHistories')->findOrFail($id);
            return view('client.history.order-detail', compact('order'));
        }



}
