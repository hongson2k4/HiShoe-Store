<?php

use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\LikeController;
use App\Http\Controllers\Client\CartController;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/get-products', function (Request $request) {
    $query = $request->query('query');
    $products = Products::where('name', 'LIKE', "%{$query}%")
        ->get(['id', 'name', 'price', 'image_url'])
        ->map(function ($product) {
            // Thêm /storage vào đường dẫn ảnh
            $product->image_url = $product->image_url ? url('storage/' . $product->image_url) : '/default-image.jpg';
            return $product;
        });

    if ($products->isEmpty()) {
        return response()->json([], 200); // Trả về mảng rỗng thay vì lỗi
    }

    return response()->json($products);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-variant-price', [ProductController::class, 'getVariantPrice']);

Route::middleware(['auth:web'])->post('/cart/add', [CartController::class, 'addToCart']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/products/{product}/like', [LikeController::class, 'toggleLike']);
// });