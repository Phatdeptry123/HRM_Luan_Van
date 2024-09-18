<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
{
        /**
     * Lấy danh sách tất cả các manager.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Lấy tất cả những user mà có ít nhất 1 user khác có manager_id là id của họ
        $managers = User::whereHas('managedUsers')->get();

        return response()->json($managers);
    }

    public function getSubordinates($managerId)
    {
        // Tìm tất cả các user có manager_id là $managerId
        $subordinates = User::where('manager_id', $managerId)->get();

        if ($subordinates->isEmpty()) {
            return response()->json([
                'message' => 'Không có cấp dưới cho user này.'
            ], 404);
        }

        return response()->json($subordinates);
    }

        /**
     * Lấy danh sách tất cả các manager và cấp dưới của họ.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getManagersWithSubordinates()
    {
        // Lấy tất cả những user mà có ít nhất 1 user khác có manager_id là id của họ
        $managers = User::whereHas('managedUsers')
            ->with('managedUsers') // Load danh sách cấp dưới của từng manager
            ->get();

        if ($managers->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy manager nào có cấp dưới.'
            ], 404);
        }

        return response()->json($managers);
    }

        /**
     * Cập nhật người quản lý cho một user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateManager(Request $request, $userId)
    {
        // Xác thực đầu vào (manager_id phải tồn tại và là số)
        $request->validate([
            'manager_id' => 'nullable|exists:users,id'
        ]);

        // Tìm user theo id
        $user = User::find($userId);

        // Nếu không tìm thấy user thì trả về lỗi 404
        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy user.'
            ], 404);
        }

        // Cập nhật manager_id cho user
        $user->manager_id = $request->input('manager_id');
        $user->save();

        return response()->json([
            'message' => 'Cập nhật người quản lý thành công.',
            'user' => $user
        ]);
    }

    /**
     * Thêm 1 cấp dưới cho người quản lý.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $managerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSubordinate(Request $request, $managerId)
    {
        // Xác thực đầu vào (user_id phải tồn tại và là số)
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Tìm user theo id (cấp dưới muốn thêm)
        $subordinate = User::find($request->input('user_id'));

        // Tìm người quản lý theo id
        $manager = User::find($managerId);

        // Nếu không tìm thấy người quản lý thì trả về lỗi 404
        if (!$manager) {
            return response()->json([
                'message' => 'Không tìm thấy người quản lý.'
            ], 404);
        }

        // Cập nhật manager_id của user thành $managerId
        $subordinate->manager_id = $managerId;
        $subordinate->save();

        return response()->json([
            'message' => 'Đã thêm cấp dưới thành công cho người quản lý.',
            'manager' => $manager,
            'subordinate' => $subordinate
        ]);
    }
}
