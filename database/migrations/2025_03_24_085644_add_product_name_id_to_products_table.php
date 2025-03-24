<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductNameIdToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thêm cột product_name_id
            $table->bigInteger('product_name_id')->unsigned()->nullable()->after('voucher_id');

            // Thiết lập khóa ngoại
            $table->foreign('product_name_id')->references('product_name_id')->on('products')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['product_name_id']);

            // Xóa cột product_name_id
            $table->dropColumn('product_name_id');
        });
    }
}