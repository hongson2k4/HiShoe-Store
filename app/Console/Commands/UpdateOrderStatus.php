<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class UpdateOrderStatus extends Command
{
    protected $signature = 'orders:update-status';
    protected $description = 'Update order status from "Đã giao hàng" to "Đã nhận hàng" after 7 days';

    public function handle()
    {
        $now = Carbon::now();
        $orders = Order::where('status', 4)->get();
        $updatedCount = 0;

        foreach ($orders as $order) {
            $daysSinceUpdate = $now->diffInDays(Carbon::parse($order->updated_at));
            if ($daysSinceUpdate >= 7) {
                $order->status = 7;
                $order->updated_at = $now;
                $order->save();
                $updatedCount++;
            }
        }

        $this->info("Đã cập nhật trạng thái cho {$updatedCount} đơn hàng từ 'Đã giao hàng' sang 'Đã nhận hàng'.");
        return 0;
    }
}