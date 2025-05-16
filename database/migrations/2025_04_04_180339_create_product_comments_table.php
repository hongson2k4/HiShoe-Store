<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('product_id'); // ID sản phẩm
            $table->unsignedBigInteger('user_id')->nullable(); // ID người dùng (nếu có)
            $table->string('name')->nullable(); // Tên khách (nếu không đăng nhập)
            $table->string('email')->nullable(); // Email khách
            $table->text('content'); // Nội dung bình luận
            $table->tinyInteger('rating')->nullable(); // 1-5 sao
            $table->unsignedBigInteger('parent_id')->nullable(); // Trả lời bình luận khác
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('product_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_comments');
    }
}
