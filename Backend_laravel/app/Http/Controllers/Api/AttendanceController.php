<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
// Import model User để tìm kiếm theo username

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        // Tìm người dùng theo username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }

        // Kiểm tra attendance hôm nay
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($attendance) {
            return response()->json(['message' => 'Người dùng đã check-in hôm nay'], 400);
        }

        // Tạo mới attendance
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'check_in' => now()->toTimeString(),
            'status' => 'on_time',
            'notes' => $request->notes,
        ]);

        return response()->json($attendance, 201);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if ($attendance) {
            // Xóa bỏ các lần check-out trước đó
            Attendance::where('user_id', $attendance->user_id)
                ->where('date', now()->toDateString())
                ->whereNotNull('check_out')
                ->delete();

            // Cập nhật giờ checkout
            $attendance->update([
                'check_out' => now()->toTimeString(),
                'status' => 'on_time', // Cập nhật trạng thái
            ]);

            return response()->json($attendance, 200);
        }

        return response()->json(['message' => 'Attendance not found'], 404);
    }

    public function checkAttendance($username)
    {
        try {
            $user = User::where('username', $username)->first();

            if (!$user) {
                return response()->json(['message' => 'Người dùng không tồn tại'], 404);
            }

            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', now()->toDateString())
                ->first();
            $message = '';
            if ($attendance) {
                $attendance->update([
                    'check_out' => now()->toTimeString(),
                    'status' => 'on_time',
                ]);
                $message = 'Check-out thành công';
            } else {
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'date' => now()->toDateString(),
                    'check_in' => now()->toTimeString(),
                    'status' => 'on_time',
                ]);
                $message = 'Check-in thành công';
            }

            return response()->json(['message' => $message, 'attendance' => $attendance], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi', 'error' => $e->getMessage()], 500);
        }
    }

}
