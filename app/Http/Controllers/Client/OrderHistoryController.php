<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        public function cancel($id)
        {
            $order = Order::where('user_id', auth()->id())->findOrFail($id);
        
            if (!$order->canCancel()) {
                return redirect()->back()->with('error', 'Đơn hàng không thể hủy! Chỉ có thể hủy đơn hàng trong vòng 24 giờ và khi trạng thái là "Đơn đã đặt" hoặc "Đang đóng gói".');
            }
        
            // Lấy danh sách sản phẩm trong đơn hàng
            $orderItems = $order->orderItemHistories;
            foreach ($orderItems as $item) {
                $variant = \App\Models\ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->stock += $item->quantity;
                    $variant->save();
                }
            }
        
            // Cập nhật trạng thái
            $order->status = 5;
            $order->updated_at = now();
            $order->save();
        
            return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');
        }

        //Chuyển trạng thái từ 4 (Đã giao hàng) sang 7 (Đã nhận hàng).
        public function receive($id)
        {
            $order = Order::where('user_id', auth()->id())->findOrFail($id);

            // Kiểm tra trạng thái hiện tại
            if ($order->status != 4) {
                return redirect()->back()->with('error', 'Đơn hàng không thể xác nhận nhận hàng! Trạng thái không hợp lệ.');
            }

            // Cập nhật trạng thái sang "Đã nhận hàng" (status = 7)
            $order->status = 7;
            $order->updated_at = now();
            $order->save();

            return redirect()->back()->with('success', 'Xác nhận đã nhận hàng thành công!');
        }

        //Placeholder cho tính năng đánh giá sản phẩm. Có thể mở rộng logic này để lưu đánh giá vào database.
        public function review($id)
        {
            $order = Order::where('user_id', auth()->id())->findOrFail($id);

            // Kiểm tra trạng thái hiện tại
            if ($order->status != 7) {
                return redirect()->back()->with('error', 'Đơn hàng không thể đánh giá! Trạng thái không hợp lệ.');
            }

            // Logic đánh giá sản phẩm (tạm thời là placeholder)
            // Bạn có thể thêm logic để lưu đánh giá vào database, ví dụ: lưu vào bảng reviews
            return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
        }

    //Controller xử lý mua lại ở trạng thái đã hủy và đã trả hàng
    public function rebuy($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Kiểm tra trạng thái đơn hàng
        if ($order->status != 5 && $order->status != 6) {
            return redirect()->back()->with('error', 'Chỉ có thể mua lại đơn hàng đã hủy hoặc đã trả hàng!');
        }

        // Lấy danh sách sản phẩm từ đơn hàng cũ
        $orderItems = $order->orderItemHistories;

        // Tạo đơn hàng mới (bỏ các cột không tồn tại)
        $newOrder = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $order->total_price,
            'status' => 1, // Đơn đã đặt
            'shipping_address' => $order->shipping_address,
            'voucher_id' => $order->voucher_id,
            'notes' => $order->notes,
        ]);

        // Thêm các sản phẩm từ đơn hàng cũ vào đơn hàng mới
        foreach ($orderItems as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $newOrder->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Chuyển hướng người dùng đến trang giỏ hàng hoặc trang thanh toán
        return redirect()->route('order-history')->with('success', 'Đơn hàng đã được tạo lại thành công! Vui lòng kiểm tra lịch sử đơn hàng.');
    }

    public function refund($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Kiểm tra trạng thái hiện tại
        if ($order->status != 7) {
            return redirect()->back()->with('error', 'Đơn hàng không thể trả hàng! Trạng thái không hợp lệ.');
        }

        // Cập nhật trạng thái sang "Đã trả hàng" (status = 6)
        $order->status = 6;
        $order->updated_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Yêu cầu trả hàng/hoàn tiền đã được gửi thành công!');
    }

    //Controller xử lý liên hệ shop
    public function contactShop(Request $request)
    {
        try {
            // Kiểm tra người dùng đã đăng nhập chưa
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để liên hệ với shop.'], 401);
            }

            // Lấy ID đơn hàng từ request
            $orderId = $request->input('order_id');
            if (!$orderId) {
                return response()->json(['success' => false, 'message' => 'ID đơn hàng không hợp lệ.'], 400);
            }

            // Tìm đơn hàng
            $order = Order::findOrFail($orderId);

            // Ghi log để kiểm tra
            Log::info('Contact Shop Request', [
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'order_user_id' => $order->user_id,
                'status' => $order->status
            ]);

            // Kiểm tra xem người dùng có quyền liên hệ cho đơn hàng này không
            if ($order->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền liên hệ cho đơn hàng này.'], 403);
            }

            // Kiểm tra trạng thái đơn hàng (chỉ cho phép liên hệ với trạng thái 1, 2, 3)
            if (!in_array($order->status, [1, 2, 3])) {
                return response()->json(['success' => false, 'message' => 'Không thể liên hệ cho đơn hàng này.'], 400);
            }

            // Cập nhật trạng thái cần hỗ trợ
            $order->needs_support = 1;
            $order->save();

            // Trả về JSON thành công
            return response()->json(['success' => true, 'message' => 'Yêu cầu hỗ trợ đã được gửi đến shop.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Đơn hàng không tồn tại
            Log::error('Contact Shop Error: Order not found', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Đơn hàng không tồn tại.'], 404);
        } catch (\Exception $e) {
            // Ghi log lỗi
            Log::error('Contact Shop Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xử lý yêu cầu: ' . $e->getMessage()], 500);
        }
    }

}
