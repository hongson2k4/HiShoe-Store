<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table("users")->insert(
            [
                ['id' => 1, 'username' => 'admin', 'password' => bcrypt("admin"), 'full_name' => 'admin', 'avatar' => '0', 'email' => 'admin@mail.com', 'phone_number' => '0123456789', 'address' => '0', 'role' => '1'],
                ['id' => 2, 'username' => 'datahihi1', 'password' => bcrypt("datahihi1"), 'full_name' => 'Nguyễn Duy Đạt', 'avatar' => '0', 'email' => 'datahihi1100@gmail.com', 'phone_number' => '0377482313', 'address' => '12541, 338, 34', 'role' => '0']
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
