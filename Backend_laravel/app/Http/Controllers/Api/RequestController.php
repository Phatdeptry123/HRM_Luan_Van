<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\Salary;
use App\Models\User;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
class RequestController extends Controller
{
    /**
     * Hiển thị danh sách yêu cầu của người dùng.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = Request::where('user_id', Auth::id())->get();
        return response()->json($requests);
    }

    /**
     * Tạo một yêu cầu mới.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:leave,remote,late',
            'description' => 'nullable|string',
            'request_date' => 'required|date',
            'manager_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $requestData = Request::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'request_date' => $validated['request_date'],
            'manager_id' => $validated['manager_id'],
        ]);

        return response()->json($requestData, 201);
    }

    /**
     * Hiển thị thông tin chi tiết của một yêu cầu.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = Request::findOrFail($id);
        return response()->json($request);
    }

    /**
     * Duyệt một yêu cầu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $request = Request::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        return response()->json($request);
    }

    /**
     * Từ chối một yêu cầu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $request = Request::findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        return response()->json($request);
    }

    /**
     * Hiển thị danh sách yêu cầu của người dùng.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRequestsForUser($id)
    {
        $requests = Request::where('user_id', $id)->get();
        return response()->json($requests);
    }

    /**
     * Hiển thị danh sách yêu cầu của người quản lý.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRequestsForManager($id)
    {
        // Sử dụng with('user') để lấy thêm thông tin của người gửi
        $requests = Request::where('manager_id', $id)
                    ->with('user:id,name') // Chỉ lấy 'id' và 'name' từ bảng User
                    ->get();
    
        return response()->json($requests);
    }
}
