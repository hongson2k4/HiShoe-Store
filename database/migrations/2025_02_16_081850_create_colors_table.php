<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('colors', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('code')->nullable();
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::table('colors', function (Blueprint $table) {
        $table->dropTimestamps(); // Xóa nếu rollback
    });
}
};
