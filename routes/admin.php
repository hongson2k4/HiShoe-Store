<?php

use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Auth;
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
    });

Route::middleware(['admin'])->controller(BrandController::class)
    ->name('brands.')
    ->prefix('admin/brands/')
    ->group(function(){
        Route::get('/', [BrandController::class, 'index'])->name('list');
        Route::get('create', [BrandController::class, 'create'])->name('create');
        Route::post('create', [BrandController::class, 'store']);
        Route::delete('delete/{id}', [BrandController::class, 'delete'])->name('delete');
        Route::get('edit/{id}', [BrandController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [BrandController::class, 'update'])->name('update');

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
    });
