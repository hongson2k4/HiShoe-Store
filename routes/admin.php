<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\AuthController;

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

Route::controller(AuthController::class)
->name('admin.')
->prefix('admin/')
->group(function () {
    Route::get('/login-form', [AuthController::class, 'loginForm'])->name('loginForm');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['admin'])->get('/admin/dashboard', function () {
    return view('admin/dashboard');
})->name('admin.dashboard');

Route::middleware(['admin'])->controller(UserController::class)
    ->name('users.')
    ->prefix('admin/users/')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('/ban/{id}', [UserController::class, 'ban'])->name('ban');
    });
