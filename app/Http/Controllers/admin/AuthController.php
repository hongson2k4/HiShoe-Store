<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Products;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function index(){
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 1) {
            return Redirect::route('admin.dashboard');
        }
        return view('admin.login');
    }
    public function loginForm(){
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 1) {
            return Redirect::route('admin.dashboard')->with('error', 'Bạn đã đăng nhập!');
        }
        return view('admin.login');
    }
    public function login(Request $request){
        if (Auth::guard('admin')->attempt(['username'=>$request->input('username'), 'password'=>$request->input('password')]) ){
            if (Auth::guard('admin')->user()->role == 1) {
               
                return redirect()->route('admin.dashboard');
            }
            session()->flash('error', 'Bạn không thể truy cập vào khu vực này!');
            return redirect('/admin/login');
        }
    
        session()->flash('error', 'Sai thông tin đăng nhập!');
        return redirect()->route('admin.loginForm');
    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('home');
    }
    public function dashboard(Request $request)
    {
        $pro = Products::count();
        $user = User::count();
        $order = Order::count();
        $comment = Comment::count();
        $brand = Brand::count();
        $cat = Category::count();
        $vou = Voucher::count();
        $doanhthu = Order::where('status', '!=', 'pending')->sum('total_price');
        
        $type = $request->input('type', 'day'); // Mặc định là 'day'
        
        // Xử lý dữ liệu biểu đồ theo type
        switch ($type) {
            case 'day':
                // Doanh thu theo ngày trong tuần (7 ngày gần nhất)
                $labels = [];
                $chartData = [];
                
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dateString = $date->format('d/m/Y');
                    $labels[] = $dateString;
                    
                    $revenue = Order::where('status', '!=', 'pending')
                                   ->whereDate('created_at', $date->toDateString())
                                   ->sum('total_price');
                    $chartData[] = $revenue;
                }
                break;
                
            case 'week':
                // Doanh thu theo tuần (4 tuần gần nhất)
                $labels = [];
                $chartData = [];
                
                for ($i = 3; $i >= 0; $i--) {
                    $startDate = now()->subWeeks($i)->startOfWeek();
                    $endDate = now()->subWeeks($i)->endOfWeek();
                    $label = 'Tuần ' . (now()->subWeeks($i)->weekOfMonth) . ' (' . 
                             $startDate->format('d/m') . ' - ' . $endDate->format('d/m') . ')';
                    $labels[] = $label;
                    
                    $revenue = Order::where('status', '!=', 'pending')
                                   ->whereBetween('created_at', [$startDate, $endDate])
                                   ->sum('total_price');
                    $chartData[] = $revenue;
                }
                break;
                
            case 'month':
                // Doanh thu theo tháng (12 tháng trong năm hiện tại)
                $labels = [];
                $chartData = [];
                
                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = 'Tháng ' . $i;
                    $revenue = Order::where('status', '!=', 'pending')
                                   ->whereYear('created_at', now()->year)
                                   ->whereMonth('created_at', $i)
                                   ->sum('total_price');
                    $chartData[] = $revenue;
                }
                break;
                
            case 'year':
                // Doanh thu theo năm (5 năm gần nhất)
                $labels = [];
                $chartData = [];
                
                for ($i = 4; $i >= 0; $i--) {
                    $year = now()->year - $i;
                    $labels[] = 'Năm ' . $year;
                    
                    $revenue = Order::where('status', '!=', 'pending')
                                   ->whereYear('created_at', $year)
                                   ->sum('total_price');
                    $chartData[] = $revenue;
                }
                break;
                
            default:
                // Mặc định hiển thị theo ngày
                $labels = [];
                $chartData = [];
                
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dateString = $date->format('d/m/Y');
                    $labels[] = $dateString;
                    
                    $revenue = Order::where('status', '!=', 'pending')
                                   ->whereDate('created_at', $date->toDateString())
                                   ->sum('total_price');
                    $chartData[] = $revenue;
                }
        }
        
        return view('admin.dashboard', [
            'pro' => $pro,
            'brand' => $brand,
            'vou' => $vou,
            'cat' => $cat,
            'user' => $user,
            'order' => $order,
            'comment' => $comment,
            'doanhthu' => $doanhthu,
            'labels' => $labels,
            'chartData' => $chartData,
            'type' => $type
        ]);
    }
}
