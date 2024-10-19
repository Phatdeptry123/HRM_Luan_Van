<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $user_id = 2;
        $month = 10;  // Tháng 9
        $year = 2024;

        // Lặp qua các ngày trong tháng 9/2024
        for ($day = 1; $day <= 30; $day++) {
            // Tạo ngày trong tháng
            $date = Carbon::create($year, $month, $day);
            
            // Kiểm tra xem ngày này có phải là ngày cuối tuần không
            $isWeekend = $date->isWeekend();

            // Tùy chỉnh giờ check_in, check_out và status dựa vào ngày làm việc
            $check_in = $isWeekend ? null : '08:00:00';
            $check_out = $isWeekend ? null : '17:00:00';
            $status = $isWeekend ? 'absent' : 'on_time';  // Vắng mặt vào cuối tuần
            
            $attendances[] = [
                'user_id' => $user_id,
                'date' => $date->format('Y-m-d'),
                'check_in' => $check_in,
                'check_out' => $check_out,
                'status' => $status,
                'notes' => $isWeekend ? 'Ngày cuối tuần, không đi làm' : 'Làm việc đúng giờ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('attendances')->insert($attendances);
    }
}
