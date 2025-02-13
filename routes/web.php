<?php

use App\Http\Controllers\admin\UserController;
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
});

Route::get('/admin/login', function () {
    return view('admin/login');
});

Route::get('/admin/dashboard', function () {
    return view('admin/dashboard');
});

Route::controller(UserController::class)
    ->name('users.')
    ->prefix('admin/users/')
    ->group(function () {
        Route::get('/', [UserController::class,'index'])->name('list');
        Route::get('/create', [UserController::class,'create'])->name('create');
        Route::post('store/',[UserController::class,'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class,'edit'])->where('id','[0-9]+')->name('edit');
        Route::put('/update/{id}', [UserController::class,'update'])->where('id','[0-9]+')->name('update');
        Route::delete('destroy/{id}',[UserController::class,'destroy'])->where('id','[0-9]+')->name('destroy');
    })
;