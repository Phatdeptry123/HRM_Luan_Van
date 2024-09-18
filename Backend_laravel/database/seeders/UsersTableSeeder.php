<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'Admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // Hash mật khẩu
            'duty' => 'Manager', // Chức vụ
            'phone' => '0123456789', // Số điện thoại
            'address' => '123 Main Street', // Địa chỉ
            'birthday' => '1990-01-01', // Ngày sinh
            'role' => 1, // Vai trò
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Trần Tấn Phát',
            'username' => 'PhatTT',
            'email' => 'phat@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // Hash mật khẩu
            'duty' => 'Developer', // Chức vụ
            'phone' => '0123456789', // Số điện thoại
            'address' => '123 Main Street', // Địa chỉ
            'birthday' => '1990-01-01', // Ngày sinh
            'role' => 2, // Vai trò
            'remember_token' => Str::random(10),
            'manager_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Nguyễn Thị Ngọc',
            'username' => 'NgocNT',
            'email' => 'ngoc@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // Hash mật khẩu
            'duty' => 'Developer', // Chức vụ
            'phone' => '0123456789', // Số điện thoại
            'address' => '123 Main Street', // Địa chỉ
            'birthday' => '1990-01-01', // Ngày sinh
            'role' => 2, // Vai trò
            'remember_token' => Str::random(10),
            'manager_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Đỗ Nguyễn Lam Thuyên',
            'username' => 'ThuyenDNL',
            'email' => 'thuyen@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // Hash mật khẩu
            'duty' => 'Developer', // Chức vụ
            'phone' => '0123456789', // Số điện thoại
            'address' => '123 Main Street', // Địa chỉ
            'birthday' => '1990-01-01', // Ngày sinh
            'role' => 2, // Vai trò
            'remember_token' => Str::random(10),
            'manager_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
