<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OvertimeController extends Controller
{
    // Hiển thị danh sách yêu cầu tăng ca của người dùng.
    public function index()
    {
        $requests = Overtime::where('user_id', Auth::id())->get(); // Sử dụng Overtime thay vì Request
        return response()->json($requests);
    }

    // Tạo một yêu cầu tăng ca mới.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'request_date' => 'required|date',
            'manager_id' => 'required|exists:users,id',
            'request_hour' => 'required|integer|min:1', // Bổ sung yêu cầu số giờ tăng ca
        ]);

        // Tạo yêu cầu tăng ca mới
        $overtime = Overtime::create([
            'user_id' => $validated['user_id'],
            'description' => $validated['description'],
            'request_date' => $validated['request_date'],
            'request_hour' => $validated['request_hour'],
            'manager_id' => $validated['manager_id'],
            'status' => 'pending', // Trạng thái mặc định là 'pending'
        ]);

        return response()->json($overtime, 201);
    }

    // Hiển thị thông tin chi tiết của một yêu cầu tăng ca.
    public function show($id)
    {
        $overtime = Overtime::findOrFail($id); // Sử dụng Overtime thay vì Request
        return response()->json($overtime);
    }

    // Duyệt yêu cầu tăng ca.
    public function approve($id)
    {
        $overtime = Overtime::findOrFail($id); // Sử dụng Overtime thay vì Request
        $overtime->status = 'approved';
        $overtime->save();

        return response()->json($overtime);
    }

    // Từ chối yêu cầu tăng ca.
    public function reject($id)
    {
        $overtime = Overtime::findOrFail($id); // Sử dụng Overtime thay vì Request
        $overtime->status = 'rejected';
        $overtime->save();

        return response()->json($overtime);
    }

    // Hiển thị danh sách tăng ca của người dùng.
    public function overtimeList($id)
    {
        $overtimes = Overtime::where('user_id', $id)
            ->with('user:id,name,avatar_img_url') // Chỉ lấy 'id', 'name' và 'avatar
            ->get();
        return response()->json($overtimes);
    }

    // Hiển thị danh sách yêu cầu tăng ca của người quản lý.
    public function getOvertimeRequestsForManager($id)
    {
        $requests = Overtime::where('manager_id', $id)
            ->with('user:id,name,avatar_img_url') // Chỉ lấy 'id' và 'name' từ bảng User
            ->get();
        return response()->json($requests);
    }

    // tổng số giờ OT của tất cả nhân viên trong tháng hiện tại
    public function totalOvertimeHoursInMonthForAllUsers()
    {
        $totalOvertimeHours = Overtime::whereMonth('request_date', now()->month)
            ->whereYear('request_date', now()->year)
            ->sum('request_hour');
        return response()->json($totalOvertimeHours);
    }

    /**
     * Get total overtime hours for the last 12 months.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyOvertimeHours()
    {
        $data = Overtime::select(
            DB::raw("DATE_FORMAT(request_date, '%Y-%m') as month"),
            DB::raw("SUM(request_hour) as total_hours")
        )
            ->where('request_date', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }

    public function getUserOvertimeRanking()
    {
        // Lấy tháng hiện tại
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Lấy dữ liệu overtime của các user trong tháng hiện tại, tính tổng số giờ overtime của từng user
        $ranking = Overtime::whereYear('request_date', $currentYear)
            ->whereMonth('request_date', $currentMonth)
            ->selectRaw('user_id, sum(request_hour) as total_hours')
            ->groupBy('user_id')
            ->orderByDesc('total_hours')
            ->get();

        // Lấy thông tin đầy đủ của từng user trong bảng xếp hạng
        $usersWithOvertime = $ranking->map(function ($overtime) {
            $user = User::find($overtime->user_id);
            return [
                'user' => $user,
                'total_hours' => $overtime->total_hours,
            ];
        });

        return response()->json($usersWithOvertime);
    }
}
