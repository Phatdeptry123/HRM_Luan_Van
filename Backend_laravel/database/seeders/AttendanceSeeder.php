<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = [];
        $month = 10;
        $year = 2024;

        // Lấy tất cả user từ bảng users
        $users = User::all();

        // Lặp qua từng người dùng
        foreach ($users as $user) {
            // Lặp qua các ngày trong tháng 10/2024
            for ($day = 1; $day <= 31; $day++) {
                // Tạo ngày trong tháng
                $date = Carbon::create($year, $month, $day);

                // Kiểm tra xem ngày này có phải là ngày cuối tuần không
                if ($date->isWeekend()) {
                    continue; // Bỏ qua nếu là thứ 7, chủ nhật
                }

                // Tùy chỉnh giờ check_in, check_out và status dựa vào ngày làm việc
                $check_in = '08:00:00';
                $check_out = '17:00:00';
                $status = 'on_time';  // Làm việc đúng giờ

                $attendances[] = [
                    'user_id' => $user->id,  // Lưu id của từng user
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $check_in,
                    'check_out' => $check_out,
                    'status' => $status,
                    'notes' => 'Làm việc đúng giờ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // Chèn tất cả dữ liệu vào bảng attendances
        DB::table('attendances')->insert($attendances);
    }
}
