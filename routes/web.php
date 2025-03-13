<?php

//Link controller
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\Products_variantController;
use App\Http\Controllers\admin\SizeController;
use App\Http\Controllers\admin\ColorController;
use App\Http\Controllers\admin\OrderController;


//link model
use App\Models\Users;
use App\Models\Products;
use App\Models\Products_variant;

//link quan trọng của toàn hệ thống
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

Route::get('/', function () {
    return view('client/home');
})->name('home');

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
        Route::get('/search', [ProductsController::class, 'search']);
    })
;

Route::middleware(['admin'])->controller(Products_variantController::class)
    ->name('products_variant.')
    ->prefix('admin/products_variant')
    ->group(function () {
        Route::get('/', [Products_variantController::class, 'index'])->name('list');
        Route::get('/create', [Products_variantController::class, 'create'])->name('create');
        Route::post('/store', [Products_variantController::class, 'store'])->name('store');
    })
;
Route::resource('admin/sizes', SizeController::class);
Route::resource('admin/colors', ColorController::class);
Route::put('/colors/{id}', [ColorController::class, 'update'])->name('colors.update');

Route::prefix('admin/orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
