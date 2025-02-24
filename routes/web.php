<?php

use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ProductsController;
use App\Models\Users;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Client\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)
->prefix('')
->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-form', [AuthController::class, 'loginForm'])->name('loginForm');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register-form', [AuthController::class, 'registerForm'])->name('registerForm');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/admin/logout', function () {
    session()->forget('user');
    return redirect()->route('home');
})->name('admin.logout');

Route::middleware(['admin'])->get('/admin/dashboard', function () {
    return view('admin/dashboard');
})->name('admin.dashboard');

Route::middleware(['admin'])->controller(UserController::class)
    ->name('users.')
    ->prefix('admin/users/')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('store/', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->where('id', '[0-9]+')->name('update');
        Route::delete('destroy/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+')->name('destroy');
    })
;

Route::middleware(['admin'])->controller(ProductsController::class)
    ->name('products.')
    ->prefix('admin/products/')
    ->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('list');
        Route::get('/create', [ProductsController::class, 'create'])->name('create');
        Route::post('/store', [ProductsController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductsController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', [ProductsController::class, 'updateProduct'])->where('id', '[0-9]+')->name('update');
        Route::delete('destroy/{id}', [ProductsController::class, 'destroyProduct'])->where('id', '[0-9]+')->name('destroy');
    })
;

Route::controller(AuthController::class)
->name('password.')
->prefix('password/')
->group(function(){
    Route::get('change',[AuthController::class,'changePass'])->name('change');
    Route::post('changePost',[AuthController::class,'storeChange'])->name('changeForm');
    Route::get('/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('request');
    Route::post('/forgot', [AuthController::class, 'sendResetLinkEmail'])->name('email');
    Route::get('/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset');
    Route::post('/reset', [AuthController::class, 'reset'])->name('update');
});
