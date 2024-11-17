<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Lấy danh sách tất cả công việc
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    // Tạo mới công việc
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:todo,pending,in_progress,completed,cancelled',
        ]);

        $task = Task::create($validatedData);

        return response()->json($task, 201);
    }

    // Hiển thị chi tiết một công việc
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    // Cập nhật công việc
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'nullable|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:todo,pending,in_progress,completed,cancelled',
        ]);

        $task->update($validatedData);

        return response()->json($task);
    }

    // Xóa công việc
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    // Hiển thị danh sách công việc của người dùng
    public function userTasks($userId)
    {
        $tasks = Task::where('created_by', $userId)->get();
        return response()->json($tasks);
    }

    // Hiển thị danh sách công việc được giao của người dùng
    public function assignedTasks($userId)
    {
        $tasks = Task::where('assigned_to', $userId)->get();
        return response()->json($tasks);
    }

    // Hiển thị danh sách công việc cuả người dùng hoặc công việc được giao của người dùng
    public function userOrAssignedTasks($userId)
    {
        $tasks = Task::where('created_by', $userId)
            ->orWhere('assigned_to', $userId)
            ->get();

        return response()->json($tasks);
    }

    // Cập nhật trạng thái công việc
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:todo,pending,in_progress,completed,cancelled',
        ]);

        $task->update($validatedData);

        return response()->json($task);
    }

    // Cập nhật người được giao công việc
    public function updateAssignedTo(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validatedData = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task->update($validatedData);

        return response()->json($task);
    }

    // Cập nhật hạn hoàn thành công việc
    public function updateDueDate(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validatedData = $request->validate([
            'due_date' => 'required|date',
        ]);

        $task->update($validatedData);

        return response()->json($task);
    }
}
