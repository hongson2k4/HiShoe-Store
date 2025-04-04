<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ColorController;
use App\Http\Controllers\admin\ProductVariantController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SizeController;
use App\Http\Controllers\admin\UserHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\OrderController;
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
        Route::get('/show/{id}', [UserController::class, 'show'])->name('show');
        Route::post('/ban/{id}', [UserController::class, 'ban'])->name('ban');
        Route::get('/unban/{id}', [UserController::class, 'unban'])->name('unban');
        Route::get('/history/{id}', [UserHistoryController::class, 'index'])->name('history');
    });

Route::middleware(['admin'])->controller(BrandController::class)
    ->prefix('admin/brands')
    ->name('brands.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{brand}/edit', 'edit')->name('edit');
        Route::put('/{brand}', 'update')->name('update');
        Route::put('/{brand}/toggle', 'toggleStatus')->name('toggle');
});


Route::middleware(['admin'])->controller(CategoryController::class)
->name('category.')
->prefix('admin/category/')
->group(function(){
    Route::get('/', [CategoryController::class, 'index'])->name('list');
    Route::get('create', [CategoryController::class, 'create'])->name('create');
    Route::post('create', [CategoryController::class, 'store']);
    Route::delete('delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [CategoryController::class, 'update'])->name('update');

});


Route::prefix('admin/orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // route để xác nhận đơn hàng
    Route::post('/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    // route xử lý liên hệ shop
    // Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders/resolve-support/{id}', [OrderController::class, 'resolveSupport'])->name('orders.resolve-support');
});

// Route::middleware(['admin'])->controller(BrandController::class)
//     ->prefix('admin/colors')
//     ->name('colors.')
//     ->group(function () {
//         Route::get('/', 'index')->name('');
//         Route::get('/create', 'create')->name('create');
//         Route::post('/', 'store')->name('store');
//         Route::get('/{brand}/edit', 'edit')->name('edit');
//         Route::put('/{brand}', 'update')->name('update');
//         Route::put('/{brand}/toggle', 'toggleStatus')->name('toggle');
// });


Route::resource('admin/sizes', SizeController::class);
Route::resource('admin/colors', ColorController::class);
Route::put('/colors/{id}', [ColorController::class, 'update'])->name('colors.update');

Route::middleware(['admin'])->controller(ProductController::class)
    ->name('products.')
    ->prefix('admin/products/')
    ->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('list');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', [ProductController::class, 'updateProduct'])->where('id', '[0-9]+')->name('update');
        Route::delete('destroy/{id}', [ProductController::class, 'destroyProduct'])->where('id', '[0-9]+')->name('destroy');
        Route::get('/search', [ProductController::class, 'search']);
    })
;

Route::middleware(['admin'])->controller(ProductVariantController::class)
    ->name('products.variant.')
    ->prefix('admin/variant/')
    ->group(function () {
        Route::get('products/{product_id}', [ProductVariantController::class, 'index'])->name('list');
        Route::get('products/{product_id}/create', [ProductVariantController::class, 'create'])->name('create');
        Route::post('products/{product_id}/store', [ProductVariantController::class, 'store'])->name('store');
        Route::get('products/{product_id}/edit/{id}', [ProductVariantController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
        Route::put('products/{product_id}/update/{id}', [ProductVariantController::class, 'update'])->where('id', '[0-9]+')->name('update');
        Route::delete('products/{product_id}/delete/{id}', [ProductVariantController::class, 'destroy'])->where('id', '[0-9]+')->name('destroy');
    });