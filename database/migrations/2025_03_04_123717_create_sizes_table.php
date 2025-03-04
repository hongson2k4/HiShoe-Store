
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('size')->unique();
            $table->timestamps();
        });
    }
     public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropTimestamps(); // Xóa nếu rollback
        });
    }
};


