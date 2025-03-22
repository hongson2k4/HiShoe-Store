<?php
//link quan trọng của toàn hệ thống
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//link model
use App\Models\Users;
use App\Models\Products;
use App\Models\Products_variant;

//Link admin controller
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\Products_variantController;
use App\Http\Controllers\admin\SizeController;
use App\Http\Controllers\admin\ColorController;
use App\Http\Controllers\admin\OrderController;

//link client controller
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderTrackController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderHistoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('client/home');
})->name('home');

Route::controller(ProductController::class)
->prefix('shop/')
->group(function(){
    Route::get('/',[ProductController::class,'index'])->name('shop');
    Route::get('/product/{product_id}',[ProductController::class,'detail'])->name('detail');
});
Route::get('/api/get-variant-price', [ProductController::class, 'getVariantPrice']);

Route::controller(AuthController::class)
->prefix('')
->group(function () {
    Route::get('/login-form', [AuthController::class, 'loginForm'])->name('loginForm');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register-form', [AuthController::class, 'registerForm'])->name('registerForm');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::controller(AuthController::class)
->name('password.')
->prefix('password/')
->group(function(){
    Route::get('change',[AuthController::class,'changePass'])->name('change');
    Route::post('changePost',[AuthController::class,'postChangePass'])->name('changeForm');
    Route::get('/forgot', [AuthController::class, 'forgotPass'])->name('request');
    Route::post('/forgot', [AuthController::class, 'sendResetLinkEmail'])->name('email');
    Route::get('/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset');
    Route::post('/reset', [AuthController::class, 'reset'])->name('update');
});

Route::controller(UserController::class)
->name('user.')
->prefix('user/')
->group(function(){
    Route::get('profile',[UserController::class,'profile'])->name('profile');
});

Route::controller(CartController::class)
->prefix('cart/')
->group(function(){
    Route::get('/', [CartController::class, 'index'])->name('cart');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
});


Route::middleware(['auth'])->group(function () {
    // Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order.history');
    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order-history')->middleware('auth');
    Route::get('/order-history/{order}', [OrderHistoryController::class, 'show'])->name('order.history.detail');
    Route::get('/order/{id}', [OrderHistoryController::class, 'detail'])->name('order.detail');


});


Route::controller(OrderTrackController::class) //route kiểm tra tình trạng đơn hàng ko cần đăng nhập
->name('order.')
->prefix('track-order')
->group(function () {
    Route::get('/', 'form')->name('form'); // Hiển thị form nhập mã đơn hàng
    Route::post('/', 'track')->name('track'); // Xử lý tra cứu đơn hàng
    Route::get('/detail/{orderId}', 'showOrderDetails')->name('detail'); // Xem chi tiết đơn hàng
});
