<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return $request->expectsJson() ? null : route('login');
    // }

    // protected function redirectTo(Request $request): ?string
    // {
    //     if (!$request->expectsJson()) {
    //         // Kiểm tra đường dẫn truy cập để chuyển hướng đúng trang login
    //         return $request->is('admin/*') ? route('loginForm') : route('loginForm');
    //     }
    //     return null;
    // }

        protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Kiểm tra đường dẫn truy cập để chuyển hướng đúng trang login và truyền thông báo
            return $request->is('admin/*') 
                ? route('loginForm', ['error' => 'Bạn cần đăng nhập để truy cập trang admin!']) 
                : route('loginForm', ['error' => 'Đăng nhập để xem lịch sử đơn hàng!!']);
        }
        return null;
    }
}
