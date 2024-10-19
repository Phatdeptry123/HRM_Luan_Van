<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->with('user:id,name')
            ->get();
        return response()->json($overtimes);
    }

    // Hiển thị danh sách yêu cầu tăng ca của người quản lý.
    public function getOvertimeRequestsForManager($id)
    {
        $requests = Overtime::where('manager_id', $id)
            ->with('user:id,name') // Chỉ lấy 'id' và 'name' từ bảng User
            ->get();
        return response()->json($requests);
    }
}
