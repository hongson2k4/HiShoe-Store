<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Products;
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Đăng nhập để xem lịch sử đơn hàng!!');
        }
    
        // Query cơ bản: Lấy đơn hàng của người dùng hiện tại
        $query = Order::where('user_id', auth()->id())
                      ->with(['orderItemHistories.product']);
    
        // Áp dụng các điều kiện lọc
        if ($request->filled('order_id')) {
            $query->where('order_check', 'like', '%' . $request->order_id . '%');
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
    
        // Lấy danh sách đơn hàng
        $orders = $query->orderBy('created_at', 'desc')->get();
    
        // Tính số lượng loại sản phẩm và tổng số lượng sản phẩm cho mỗi đơn hàng
        foreach ($orders as $order) {
            $order->totalItems = $order->orderItemHistories->count(); // Số lượng loại sản phẩm
            $order->totalQuantity = $order->orderItemHistories->sum('quantity'); // Tổng số lượng sản phẩm
        }        
        return view('client.history.order-history', compact('orders'));
    }

    public function show($id)
    {
        // Tải đơn hàng cùng với danh sách sản phẩm và thông tin người dùng
        $order = Order::with(['orderItemHistories.product'])->findOrFail($id);
        $product = Products::with('productVariants')->findOrFail($order->product_id);
        // Kiểm tra quyền truy cập
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('order-history')->with('error', 'Bạn không có quyền xem đơn hàng này!');
        }

        return view('client.history.order-detail', compact('order'));
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Kiểm tra xem đơn hàng có thể hủy không
        if (!$order->canCancel()) {
            return redirect()->back()->with('error', 'Đơn hàng không thể hủy! Chỉ có thể hủy đơn hàng trong vòng 24 giờ và khi trạng thái là "Đơn đã đặt" hoặc "Đang đóng gói".');
        }

        // Validate lý do hủy
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        // Cập nhật trạng thái và lưu lý do hủy
        $order->status = 5; // Đã hủy
        $order->customer_reasons = $request->input('cancel_reason');
        $order->updated_at = now();

        // Cộng lại số lượng sản phẩm vào kho
        $orderItems = $order->orderItemHistories;
        foreach ($orderItems as $item) {
            $variant = \App\Models\Product_variant::find($item->variant_id);
            if ($variant) {
                $variant->stock += $item->quantity;
                $variant->save();
            }
        }

        $order->save();

        return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');
    }

    // public function cancel($id)
    // {
    //     $order = Order::where('user_id', auth()->id())->findOrFail($id);
    
    //     if (!$order->canCancel()) {
    //         return redirect()->back()->with('error', 'Đơn hàng không thể hủy! Chỉ có thể hủy đơn hàng trong vòng 24 giờ và khi trạng thái là "Đơn đã đặt" hoặc "Đang đóng gói".');
    //     }
    
    //     // Lấy danh sách sản phẩm trong đơn hàng
    //     $orderItems = $order->orderItemHistories;
    //     foreach ($orderItems as $item) {
    //         $variant = \App\Models\ProductVariant::find($item->variant_id);
    //         if ($variant) {
    //             $variant->stock += $item->quantity;
    //             $variant->save();
    //         }
    //     }
    
    //     // Cập nhật trạng thái
    //     $order->status = 5;
    //     $order->updated_at = now();
    //     $order->save();
    
    //     return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');
    // }

    public function receive($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if ($order->status != 4) {
            return redirect()->back()->with('error', 'Đơn hàng không thể xác nhận nhận hàng! Trạng thái không hợp lệ.');
        }

        $order->status = 7;
        $order->updated_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Xác nhận đã nhận hàng thành công!');
    }

    public function review($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if ($order->status != 7) {
            return redirect()->back()->with('error', 'Đơn hàng không thể đánh giá! Trạng thái không hợp lệ.');
        }

        if ($order->is_reviewed) {
            return redirect()->back()->with('error', 'Đơn hàng đã được đánh giá trước đó!');
        }

        $order->is_reviewed = 1;
        $order->updated_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function rebuy($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if ($order->status != 5 && $order->status != 6) {
            return redirect()->back()->with('error', 'Chỉ có thể mua lại đơn hàng đã hủy hoặc đã trả hàng!');
        }

        $orderItems = $order->orderItemHistories;

        $newOrder = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $order->total_price,
            'status' => 1,
            'shipping_address' => $order->shipping_address,
            'voucher_id' => $order->voucher_id,
            'notes' => $order->notes,
        ]);

        foreach ($orderItems as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $newOrder->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        return redirect()->route('order-history')->with('success', 'Đơn hàng đã được tạo lại thành công! Vui lòng kiểm tra lịch sử đơn hàng.');
    }

    public function refund($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if ($order->status != 7) {
            return redirect()->back()->with('error', 'Đơn hàng không thể trả hàng! Trạng thái không hợp lệ.');
        }

        if ($order->is_refunded) {
            return redirect()->back()->with('error', 'Đơn hàng đã được yêu cầu trả hàng/hoàn tiền trước đó!');
        }

        $order->is_refunded = 1;
        $order->needs_refunded = 1;
        $order->updated_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Yêu cầu trả hàng/hoàn tiền đã được gửi thành công! Đang chờ shop xem xét.');
    }

    public function contactShop(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để liên hệ với shop.'], 401);
            }

            $orderId = $request->input('order_id');
            if (!$orderId) {
                return response()->json(['success' => false, 'message' => 'ID đơn hàng không hợp lệ.'], 400);
            }

            $order = Order::findOrFail($orderId);

            Log::info('Contact Shop Request', [
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'order_user_id' => $order->user_id,
                'status' => $order->status
            ]);

            if ($order->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền liên hệ cho đơn hàng này.'], 403);
            }

            if (!in_array($order->status, [1, 2, 3])) {
                return response()->json(['success' => false, 'message' => 'Không thể liên hệ cho đơn hàng này.'], 400);
            }

            $order->needs_support = 1;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Yêu cầu hỗ trợ đã được gửi đến shop.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Contact Shop Error: Order not found', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Đơn hàng không tồn tại.'], 404);
        } catch (\Exception $e) {
            Log::error('Contact Shop Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xử lý yêu cầu: ' . $e->getMessage()], 500);
        }
    }
}