<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

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
