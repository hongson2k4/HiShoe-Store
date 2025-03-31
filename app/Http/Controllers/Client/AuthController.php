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
use Illuminate\Validation\Rule;

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
                'password' => [
                    'required', 
                    'regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,20}$/'
                ],
                'password_confirmation' => 'required|same:password',
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone_number' => [
                    'required',
                    'unique:users,phone_number',
                    'regex:/^(0|\+84)\d{9,10}$/',
                ]
            ], [
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.regex' => 'Mật khẩu phải từ 8-20 ký tự, không có ký tự đặc biệt, ít nhất 1 chữ in hoa và 1 số.',
                'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
                'password_confirmation.same' => 'Mật khẩu xác nhận không khớp.',
                'full_name.required' => 'Vui lòng nhập họ và tên.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
                'email.unique' => 'Email đã tồn tại.',
                'phone_number.required' => 'Vui lòng nhập số điện thoại.',
                'phone_number.unique' => 'Số điện thoại đã tồn tại.',
                'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng 0 hoặc +84 và có 10-11 chữ số.',
            ]);

            try {
                $username = explode('@', $validate['email'])[0];

                User::create([
                    'username' => $username,
                    'password' => Hash::make($validate['password']),
                    'full_name' => $validate['full_name'],
                    'email' => $validate['email'],
                    'phone_number' => $validate['phone_number'],
                    'role' => 0,
                ]);

                return redirect()->route('loginForm');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Đăng ký người dùng thất bại']);
            }
        }
        return redirect()->back()->withErrors('error', 'Yêu cầu không hợp lệ');
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
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập hoặc email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);
    
        $credentials = filter_var($validate['username'], FILTER_VALIDATE_EMAIL)
            ? ['email' => $validate['username'], 'password' => $validate['password']]
            : ['username' => $validate['username'], 'password' => $validate['password']];
    
        if (Auth::attempt($credentials)) {
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
        $request->validate([
            'email_or_phone' => 'required',
            'otp' => 'required',
            'password' => 'required|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,20}$/',
        ], [
            'password.regex' => 'Mật khẩu phải từ 8-20 ký tự, không có ký tự đặc biệt, ít nhất 1 chữ in hoa và 1 số.',
        ]);
    
        $identifier = $request->input('email_or_phone');
        $otp = $request->input('otp');
    
        $user = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? User::where('email', $identifier)->first()
            : User::where('phone_number', $identifier)->first();
    
        if (!$user || $user->otp != $otp) {
            return back()->withErrors(['otp' => 'Mã OTP không hợp lệ.']);
        }
    
        $user->update([
            'password' => bcrypt($request->input('password')),
            'otp' => null, // Xóa OTP sau khi sử dụng
        ]);
    
        return redirect()->route('loginForm')->with('status', 'Đặt lại mật khẩu thành công!');
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
            'password' => 'required|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,20}$/',
            'token' => 'required'
        ], [
            'password.regex' => 'Mật khẩu phải từ 8-20 ký tự, không có ký tự đặc biệt, ít nhất 1 chữ in hoa và 1 số.',
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
            'old_password' => 'required',
            'new_password' => 'required|min:8|different:old_password',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.different' => 'Mật khẩu mới không được trùng với mật khẩu cũ.',
            'confirm_password.required' => 'Vui lòng xác nhận lại mật khẩu.',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu mới.',
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
            'old_value' => "Không hiển thị",
            'new_value' => "Không hiển thị",
            'change_by' => Auth::id(),
            'content' => "Người dùng cập nhật mật khẩu mới",
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
        ], [
            'identifier.required' => 'Vui lòng nhập email hoặc số điện thoại.',
        ]);

        $identifier = $request->input('identifier');

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // Xử lý gửi liên kết qua email
            $user = User::where('email', $identifier)->first();
            if (!$user) {
                return back()->withErrors(['identifier' => 'Email không tồn tại trong hệ thống.']);
            }

            $response = Password::sendResetLink(['email' => $identifier]);

            return $response == Password::RESET_LINK_SENT
                ? back()->with('status', trans($response))
                : back()->withErrors(['identifier' => trans($response)]);
        } else {
            // Xử lý gửi OTP qua số điện thoại
            $user = User::where('phone_number', $identifier)->first();
            if (!$user) {
                return back()->withErrors(['identifier' => 'Số điện thoại không tồn tại trong hệ thống.']);
            }

            try {
                $otp = rand(100000, 999999); // Tạo mã OTP
                $user->update(['otp' => $otp]); // Lưu OTP vào cơ sở dữ liệu

                // Gửi OTP qua Twilio
                $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
                $twilio->messages->create(
                    $identifier,
                    [
                        'from' => env('TWILIO_PHONE_NUMBER'),
                        'body' => "Mã OTP đặt lại mật khẩu của bạn là: $otp",
                    ]
                );

                return back()->with('status', 'Mã OTP đã được gửi đến số điện thoại của bạn.');
            } catch (\Exception $e) {
                return back()->withErrors(['identifier' => 'Không thể gửi OTP. Vui lòng thử lại sau.']);
            }
        }
    }
}