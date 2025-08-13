<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        // Lấy tất cả voucher
        $vouchers = Voucher::all();

        // Cập nhật trạng thái hết hạn nếu cần
        foreach ($vouchers as $voucher) {
            if ($voucher->status != Voucher::STATUS_EXPIRED && $voucher->end_date < now()) {
                $voucher->status = Voucher::STATUS_EXPIRED;
                $voucher->save();
            }
        }

        return view('admin/vouchers/list', compact('vouchers'));
    }
    public function create()
    {
        return view('admin/vouchers/add');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'discount_type' => 'required|in:0,1',
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type == 0 && $value > 60) {
                        $fail('Giảm giá theo % chỉ được tối đa 60%.');
                    }
                }
            ],
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'required|integer|min:1',
        ]);

        $data['status'] = 1;
        Voucher::create($data);

        return redirect()->route('vouchers.list');
    }
    public function edit($id)
    {
        $voucher = Voucher::find($id);
        return view('admin/vouchers/edit', compact('voucher'));
    }
    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return redirect()->route('vouchers.list')->with('error', 'Voucher not found');
        }

        // Chỉ validate và cập nhật trường status
        $data = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $voucher->update($data);

        return redirect()->route('vouchers.list')->with('success', 'Cập nhật trạng thái thành công!');
    }
    public function delete($id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return redirect()->back()->with('error', 'Mã không tồn tại.');
        }

        \DB::table('voucher_usages')->where('voucher_id', $voucher->id)->delete();

        $voucher->delete();

        return redirect()->route('vouchers.list')->with('success', 'Xóa mã thành công!');
    }

    public function deleteExpired()
    {

        // Lấy danh sách id các voucher hết hạn
        $expiredVoucherIds = Voucher::where('end_date', '<', now())->pluck('id');

        // Xóa các usage liên quan trước
        \DB::table('voucher_usages')->whereIn('voucher_id', $expiredVoucherIds)->delete();

        // Xóa các voucher hết hạn
        $deleted = Voucher::where('end_date', '<', now())->delete();
        return redirect()->route('vouchers.list')->with('success', "Đã xóa $deleted mã giảm giá hết hạn!");
    }
}