<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserHistoryChanges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function registerForm()
    {
        if (Auth::check()) {
            return Redirect::route('home')->with('error', 'Bạn đã đăng nhập!');
        }
        return view('client.auth.register');
    }
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'full_name' => 'required',
            'email' => 'required',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png',
            'phone_number' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
        ]);

        if ($request->hasFile('avatar')) {
            $part = $request->file('avatar')->store('uploads/client/users', 'public');
        } else {
            $part = null;
        }

        try {
            $user = User::create([
                'username' => $validate['username'],
                'password' => $validate['password'],
                'full_name' => $validate['full_name'],
                'email' => $validate['email'],
                'avatar' => $part,
                'phone_number' => $validate['phone_number'],
                'address' => $validate['ward'] . ', ' . $validate['district'] . ', ' . $validate['province'],
                'role' => 0,
            ]);

            return redirect()->route('loginForm');
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to register user']);
        }
    }
    return redirect()->back()->withErrors('error', 'Invalid request');
    }
    public function loginForm()
    {
        if (Auth::check()) {
            return Redirect::route('home')->with('error', 'Bạn đã đăng nhập!');
        }
        return view('client.auth.login');
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            if (Auth::user()) {
                return redirect()->route('home');
            }
        }

        session()->flash('error', 'Sai thông tin đăng nhập!');
        return redirect()->route('loginForm');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function forgotPass()
    {
        return view('client.auth.forgot');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }
    public function showResetPasswordForm($token = null)
    {
        $email = request()->input('email');
        return view('client.auth.reset', ['token' => $token, 'email' => $email]);
    }
    public function reset(Request $request)
    {
        $this->validateReset($request);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );
        $user = User::where('email', $request->email)->first();

        UserHistoryChanges::create([
            'user_id' => $user->id,
            'field_name' => 'password',
            'old_value' => "*",
            'new_value' => "*",
            'change_by' => Auth::id(),
            'content' => "Người dùng đặt lại mật khẩu",
            'updated_at' => now(),
        ]);

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('loginForm')->with('status', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.exists' => 'Tài khoản email chưa được đăng ký trên hệ thống.'
        ]);
    }
    protected function validateReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);
    }

    public function changePass()
    {
        if (!Auth::check()) {
            return Redirect::route('loginForm')->with('error', 'Bạn cần đăng nhập!');
        }
        return view("client.pages.user.change");
    }
    public function postChangePass(Request $request)
    {
        $validate = $request->validate([
            'old_password'    => 'required',
            'new_password'    => 'required|min:8|different:old_password',
            'confirm_password'=> 'required|same:new_password',
        ], [
            'old_password.required'    => 'Vui lòng nhập mật khẩu cũ.',
            'new_password.required'    => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'         => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.different'   => 'Mật khẩu mới không được trùng với mật khẩu cũ.',
            'confirm_password.required'=> 'Vui lòng xác nhận lại mật khẩu.',
            'confirm_password.same'    => 'Xác nhận mật khẩu không khớp với mật khẩu mới.',
        ]);
    
        $user = User::find(Auth::id());
    
        if (!Hash::check($validate['old_password'], $user->password)) {
            return redirect()->back()->withErrors(['error' => 'Sai mật khẩu cũ']);
        }
        
        $user->update([
            'password' => Hash::make($validate['new_password']),
        ]);

        UserHistoryChanges::create([
            'user_id' => Auth::id(),
            'field_name' => 'password',
            'old_value' => "*",
            'new_value' => "*",
            'change_by' => Auth::id(),
            'content' => "Người dùng cập nhật mật khẩu mới",
            'updated_at' => now(),
        ]);
    
        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }
}