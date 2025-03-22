<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function loginForm(){
        if (Auth::check() && Auth::user()->role == 1) {
            return Redirect::route('admin.dashboard')->with('error', 'Bạn đã đăng nhập!');
        }
        return view('admin.login');
    }
    public function login(Request $request){
        if (Auth::attempt(['username'=>$request->input('username'), 'password'=>$request->input('password')]) ){
            if (Auth::user()) {
               
                return redirect()->route('admin.dashboard');
            }
            session()->flash('error', 'Bạn không thể truy cập vào khu vực này!');
            return redirect('/admin/login');
        }
    
        session()->flash('error', 'Sai thông tin đăng nhập!');
        return redirect()->route('admin.loginForm');
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('home');
    }
}
