<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\LikeController;
use App\Http\Controllers\Client\OrderHistoryController;
use App\Http\Controllers\Client\OrderTrackController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::controller(ProductController::class)
->prefix('shop/')
->group(function(){
    Route::get('/',[ProductController::class,'index'])->name('shop');
    Route::get('/product/{product_id}',[ProductController::class,'detail'])->name('detail');
    Route::get('/category/{category_id}',[ProductController::class,'category'])->name('category');
    Route::get('/brand/{brand_id}',[ProductController::class,'brand'])->name('brand');
});
// Route::get('/api/get-variant-price', [ProductController::class, 'getVariantPrice']);

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
    Route::post('/forgot', [AuthController::class, 'sendResetLink'])->name('sendResetLink'); // Định nghĩa route này
    Route::get('/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset');
    Route::post('/reset', [AuthController::class, 'reset'])->name('update');
});

Route::controller(UserController::class)
->name('user.')
->prefix('user/')
->group(function(){
    Route::middleware(['auth:web', 'client'])->get('profile',[UserController::class,'profile'])->name('profile');
});
Route::post('/comment/send', [CommentController::class, 'store'])->name('comment.store');

Route::middleware(['auth:web', 'client'])->group(function () {
    Route::post('/products/{product}/like', [LikeController::class, 'toggleLike'])->name('product.like');
    Route::get('/liked-products', [LikeController::class, 'likedProducts'])->name('liked.products');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/add-variant', [CartController::class, 'addVariantToCart'])->name('cart.addVariant');
});

Route::controller(CartController::class)
->prefix('cart/')
->group(function(){
    Route::get('/', [CartController::class, 'index'])->name('cart');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/delete/{id}', [CartController::class, 'destroy'])->name('cart.delete'); // New route
});

Route::controller(CartController::class)
->prefix('cart/')
->group(function(){
    Route::get('/', [CartController::class, 'index'])->name('cart');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
});


Route::middleware(['auth:web', 'client'])->group(function () {
    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order-history')->middleware('auth');
    Route::get('/order-history/{order}', [OrderHistoryController::class, 'show'])->name('order.history.detail');
    Route::get('/order/{id}', [OrderHistoryController::class, 'detail'])->name('order.detail');

    // Thêm route mới để hủy đơn hàng chuyển trang thái status = 5
    // Route::post('/order-history/{order}/cancel', [OrderHistoryController::class, 'cancel'])->name('order.history.cancel');
    // Thêm route mới để hủy đơn hàng và nhập lý do chuyển trang thái status = 5
    Route::post('/order-history/cancel/{id}', [OrderHistoryController::class, 'cancelOrder'])->name('order.history.cancel');
    // Thêm route để xác nhận đã nhận hàng
    Route::post('/order-history/{order}/receive', [OrderHistoryController::class, 'receive'])->name('order.history.receive');
    // Thêm route để đánh giá sản phẩm (tạm thời là placeholder)
    Route::post('/order-history/{order}/review', [OrderHistoryController::class, 'review'])->name('order.history.review');
    // Thêm route để mua lại đơn hàng
    Route::post('/order-history/{order}/rebuy', [OrderHistoryController::class, 'rebuy'])->name('order.history.rebuy');
    // Thêm route để xóa đơn hàng
    Route::delete('/{order}/delete', [OrderController::class, 'delete'])->name('orders.delete');
    // Thêm route để trả hàng/hoàn tiền
    Route::post('/order-history/{order}/refund', [OrderHistoryController::class, 'refund'])->name('order.history.refund');
    // Thêm route để liên hệ shop
    Route::post('order/history/contact', [OrderHistoryController::class, 'contactShop'])->name('order.history.contact');
    // Route ẩn nút trả hàng\ hoàn tiền
    Route::post('/admin/orders/{id}/resolve-refunded', [OrderController::class, 'resolveRefunded'])->name('orders.resolve-refunded');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');

});


Route::controller(OrderTrackController::class) //route kiểm tra tình trạng đơn hàng ko cần đăng nhập
->name('order.')
->prefix('track-order')
->group(function () {
    Route::get('/', 'form')->name('form'); // Hiển thị form nhập mã đơn hàng
    Route::post('/', 'track')->name('track'); // Xử lý tra cứu đơn hàng
    Route::get('/detail/{orderId}', 'showOrderDetails')->name('detail'); // Xem chi tiết đơn hàng
});

Route::middleware(['auth:web', 'client'])->group(function () {
 
    Route::get('/orders/{order_id}/review/{product_id}', [ReviewController::class, 'create'])
    ->name('orders.review.create');

// Lưu đánh giá
Route::post('/orders/{order_id}/review/{product_id}', [ReviewController::class, 'store'])
    ->name('orders.review.store');

// HIỂN THỊ đánh giá
Route::get('/orders/{order_id}/review/{product_id}/show', [ReviewController::class, 'show'])
    ->name('orders.review.show');
});