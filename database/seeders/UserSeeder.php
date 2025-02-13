<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $s = [];
        for ($i = 0; $i < 5; $i++) {
            $s[] = [
                'username' => fake()->userName(),
                'password' => fake()->password(),
                'full_name' => fake()->name(),
                'avatar' => fake()->image(),
                'email' => fake()->email(),
                'email_verified_at'=> fake()->dateTime(),
                'phone_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'role' => 0, // always users is customer
            ];
        }
        DB::table('users')->insert($s);
    }
}
