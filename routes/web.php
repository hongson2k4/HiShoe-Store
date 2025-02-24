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
