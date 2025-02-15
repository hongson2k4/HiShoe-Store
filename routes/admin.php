<?php

use App\Http\Controllers\admin\UserController;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $user = Users::where('username', $request->input('username'))->first();

    if ($user && Hash::check($request->input('password'), $user->password)) {
        session(['user' => $user]);

        if ($user->role == 1) {
            return redirect()->route('admin.dashboard');
        }
        session()->flash('error', 'Bạn không có quyền admin!');
        return redirect('/admin/login');
    }

    session()->flash('error', 'Sai thông tin đăng nhập!');
    return redirect()->route('admin.login');
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