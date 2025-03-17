<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderCheck;

class OrderTrackController extends Controller {
    public function form() 
    {
        return view('client.order.form'); // Đường dẫn chính xác đến file view
    }
    
    public function track(Request $request) {
        $order = OrderCheck::where('order_check', $request->order_check  )->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Mã đơn hàng không tồn tại!');
        }
        return view('client.order.form', compact('order'));
    }
    
}


?>