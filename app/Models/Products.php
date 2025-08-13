<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'sku_code',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'brand_id',
        'image_url',
    ];
    public $timestamp = false;
    /**
     * This method defines a one-to-many relationship with the Product_variant model.
     */
    public function variants()
    {
        return $this->hasMany(Product_variant::class, 'product_id');
    }
    /**
     * This method defines a one-to-many relationship with the Size model through the Product_variant model.
     * 
     * It retrieves all sizes associated with the product by going through its variants.
     */
    public function sizes()
    {
        return $this->hasManyThrough(Size::class, Product_variant::class, 'product_id', 'id', 'id', 'size_id');
    }
    /**
     * This method defines a one-to-many relationship with the Color model through the Product_variant model.
     * 
     * It retrieves all colors associated with the product by going through its variants.
     */
    public function colors()
    {
        return $this->hasManyThrough(Color::class, Product_variant::class, 'product_id', 'id', 'id', 'color_id');
    }
    /**
     * This method defines a one-to-many relationship with the Comment model.
     * 
     * It retrieves all comments associated with the product.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id'); // đảm bảo đúng tên cột
    }
    /**
     * This method defines a one-to-many relationship with the Order model.
     * 
     * It retrieves all orders associated with the product by its name ID.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_name_id', 'id');
    }
    /**
     * This method defines a one-to-many relationship with the Like model.
     * 
     * It retrieves all likes associated with the product.
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'product_id'); // Explicitly specify the foreign key
    }
    /**
     * This method defines a one-to-many relationship with the Review model.
     * 
     * It retrieves all reviews associated with the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    /**
     * This method defines a one-to-many relationship with the Product_categories model.
     * 
     * It retrieves all product categories associated with the product.
     */
    public function product_categories()
    {
        return $this->hasMany(Product_categories::class, 'product_id');
    }
    /**
     * This method defines a one-to-many relationship with the Product_details model.
     * 
     * It retrieves all product details associated with the product.
     */
    public function productDetails()
    {
        return $this->hasMany(Product_details::class, 'product_id');
    }
    /**
     * This method generates a random SKU code for the product.
     * 
     * The SKU code is a string of uppercase letters and digits, with a length between 12 and 15 characters.
     */
    public function generateSku($length = 12)
    {
        $length = max(12, min($length, 15)); // Giới hạn độ dài từ 12 đến 15
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomCode = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomCode;
    }
    /**
     * This method syncs the stock quantity of the product by summing the stock quantities of all its variants.
     * 
     * It updates the product's stock quantity and status based on the total stock.
     */
    public function syncStockQuantity()
    {
        $total = $this->variants()->sum('stock_quantity');
        $this->stock_quantity = $total;
        $this->status = ($total == 0) ? 1 : 0; // 1: Hết hàng, 0: Còn hàng
        $this->save();
    }
    /**
     * This method defines a one-to-many relationship with the Category model.
     * 
     * It retrieves the category associated with the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    /**
     * This method defines a one-to-many relationship with the Brand model.
     * 
     * It retrieves the brand associated with the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
