<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'orderDetails']) // eager load relationships
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->paginate(10); // Use paginate instead of get()
        return view('admin.orders.index', compact('orders'));
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
}
