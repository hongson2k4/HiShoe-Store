<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin/vouchers/list', compact('vouchers'));
        }
    public function create()
    {
        return view('admin/vouchers/add');
    }
    public function store(Request $request){

        $data = $request->validate([
            'code' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'min_order_value' => 'nullable',
            'max_discount_value' => 'nullable',
            'start_date' => 'required',
            'end_date' => 'required',
            'usage_limit' => 'required',
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
        if(!$voucher){
            return redirect()->route('vouchers.list')->with('error', 'Voucher not found');
        }
        $data = $request->validate([
            'code' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'min_order_value' => 'nullable',
            'max_discount_value' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'required',
        ]);
        if($request->has('status')){
            $data['status'] = 1;
        }else{
            $data['status'] = 0;
        }
        $voucher->update($data);
        return redirect()->route('vouchers.list');
    }
    public function delete($id)
    {
        $voucher = voucher::find($id);

        if (!$voucher) {
            return redirect()->back()->with('error', 'Mã không tồn tại.');
        }

        $voucher->delete();

        return redirect()->route('vouchers.list')->with('success', 'Xóa mã thành công!');
    }

}

