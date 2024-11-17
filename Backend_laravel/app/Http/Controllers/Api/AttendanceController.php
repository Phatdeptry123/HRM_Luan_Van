<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\Attendance;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Illuminate\Http\Request;
// Import model User để tìm kiếm theo username

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')->get();
        return response()->json($attendances);
    }

    public function getAttendanceByUserId($id)
    {
        $attendances = Attendance::where('user_id', $id)->with('user')->get();
        return response()->json($attendances);
    }

    public function show($id)
    {
        $attendance = Attendance::with('user')->find($id);

        if ($attendance) {
            return response()->json($attendance);
        }

        return response()->json(['message' => 'Attendance not found'], 404);
    }
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

    public function countDaysCheckinInMonthForAllUsers()
    {
        $approvedRequests = ModelsRequest::select('user_id')
            ->whereMonth('request_date', now()->month)
            ->whereYear('request_date', now()->year)
            ->where(function($query) {
                $query->where('type', 'leave')
                      ->orWhere('type', 'remote');
            })
            ->where('status', 'approved')
            ->get()
            ->groupBy('user_id');

        $attendances = Attendance::selectRaw('user_id, count(*) as total')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->whereTime('check_in', '<=', '09:00:00')
            ->whereTime('check_out', '>=', '16:00:00')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        foreach ($approvedRequests as $userId => $requests) {
            if (isset($attendances[$userId])) {
                $attendances[$userId]->total += $requests->count();
            } else {
                $attendances[$userId] = (object) [
                    'user_id' => $userId,
                    'total' => $requests->count()
                ];
            }
        }

        $userIds = $attendances->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $result = $attendances->map(function ($attendance) use ($users) {
            $attendance->user = $users[$attendance->user_id];
            return $attendance;
        });

        return response()->json($result->values());
    }

    public function countDaysCheckinLateOrCheckoutEarlyInMonthForAllUsers()
    {
        $attendances = Attendance::selectRaw('user_id, count(*) as total')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where(function ($query) {
                // Điều kiện đi trễ
                $query->whereTime('check_in', '>', '08:00:00')
                      ->whereTime('check_in', '<', '08:59:59');
            })
            ->orWhere(function ($query) {
                // Điều kiện về sớm
                $query->whereTime('check_out', '<', '17:00:00')
                      ->whereTime('check_out', '>', '16:00:00');
            })
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');
    
        return response()->json($attendances->values());
    }

    public function averageTimeoffInMonthForAllUsers()
    {
        $attendances = Attendance::selectRaw('user_id, count(*) as total')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where(function ($query) {
                // Điều kiện đi trễ
                $query->whereTime('check_in', '>', '08:00:00')
                      ->whereTime('check_in', '<', '08:59:59');
            })
            ->orWhere(function ($query) {
                // Điều kiện về sớm
                $query->whereTime('check_out', '<', '17:00:00')
                      ->whereTime('check_out', '>', '16:00:00');
            })
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');
    
        // Lấy danh sách các ngày trong tháng ngoại trừ thứ 7 và chủ nhật
        $daysInMonth = collect(range(1, now()->daysInMonth))->filter(function ($day) {
            $date = now()->setDay($day);
            return !$date->isWeekend(); // Bỏ qua thứ 7, chủ nhật
        });
    
        // Duyệt qua mỗi user để kiểm tra các ngày không có check-in
        foreach ($daysInMonth as $day) {
            $currentDate = now()->setDay($day)->toDateString();
    
            $usersWithoutAttendance = User::whereDoesntHave('attendances', function ($query) use ($currentDate) {
                $query->whereDate('date', $currentDate);
            })->pluck('id');
    
            foreach ($usersWithoutAttendance as $userId) {
                if (isset($attendances[$userId])) {
                    $attendances[$userId]->total += 1;
                } else {
                    $attendances[$userId] = (object) ['user_id' => $userId, 'total' => 1];
                }
            }
        }
    
        return response()->json($attendances->values());
    }
    
}
