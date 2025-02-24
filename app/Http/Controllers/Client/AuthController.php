<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register()
    {
        return view('client.auth.register');
    }
    public function registerForm(Request $request)
    {
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
            $user = Users::create([
                'username' => $validate['username'],
                'password' => $validate['password'],
                'full_name' => $validate['full_name'],
                'email' => $validate['email'],
                'avatar' => $part,
                'phone_number' => $validate['phone_number'],
                'address' => $validate['ward'] . ', ' . $validate['district'] . ', ' . $validate['province'],
                'role' => 0,
            ]);

            // Redirect or return success response
            return redirect()->route('login');
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            // Redirect or return error response
            return redirect()->back()->withErrors(['error' => 'Failed to register user']);
        }
    }
    public function login()
    {
        return view('client.auth.login');
    }
    public function loginForm(Request $request)
    {
        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            if (Auth::user()) {

                return redirect()->route('home');
            }
            session()->flash('error', 'Bạn không thể truy cập vào khu vực này!');
            return redirect('/login');
        }

        session()->flash('error', 'Sai thông tin đăng nhập!');
        return redirect()->route('login');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function showForgotPasswordForm()
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
        return view('client.auth.reset', ['token' => $token]);
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

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
    }
    protected function validateReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);
    }
}
