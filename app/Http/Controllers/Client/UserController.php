<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('loginForm')->with('error', 'Chức năng này yêu cầu đăng nhập.');
        }
        return view("client.pages.user.profile");
    }

    public function addAddress()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('loginForm')->with('error', 'Chức năng này yêu cầu đăng nhập.');
        }
        return view("client.pages.user.add_address");
    }

    public function submitAddress(Request $request)
    {
        $request->validate([
            'receive_name' => 'required|string|max:255',
            'receive_number' => 'required|string|max:255',
            'receive_address' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->ship_address()->create([
            'receive_name' => $request->input('receive_name'),
            'receive_number' => $request->input('receive_number'),
            'receive_address' => $request->input('receive_address'),
            'is_default' => 1,
        ]);

        return redirect()->route('user.profile')->with('success', 'Thêm địa chỉ thành công');
    }

}
