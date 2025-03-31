<?php

use App\Http\Controllers\Client\AuthController;
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

Route::get('/', function () {
    return view('client/home');
})->name('home');

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
    Route::get('profile',[UserController::class,'profile'])->name('profile');
});

Route::controller(CartController::class)
->prefix('cart/')
->group(function(){
    Route::get('/', [CartController::class, 'index'])->name('cart');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
});