<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use App\Models\Salary;
use App\Models\SalaryDetail;
use Illuminate\Http\Request;

class SalaryDetailController extends Controller
{
    // Thêm chi tiết lương vào phiếu lương
    public function store(Request $request, $salaryId)
    {
        $request->validate([
            'allowance' => 'nullable|numeric',
            'overtime' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'other_deductions' => 'nullable|numeric',
            'final_salary' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $salaryDetail = SalaryDetail::create([
            'salary_id' => $salaryId,
            'allowance' => $request->allowance,
            'overtime' => $request->overtime,
            'bonus' => $request->bonus,
            'other_deductions' => $request->other_deductions,
            'final_salary' => $request->final_salary,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Thêm chi tiết lương thành công!', 'salaryDetail' => $salaryDetail], 201);
    }

    // Cập nhật chi tiết lương
    public function update(Request $request, $id)
    {
        $request->validate([
            'allowance' => 'nullable|numeric',
            'overtime' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'other_deductions' => 'nullable|numeric',
            'final_salary' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $salaryDetail = SalaryDetail::findOrFail($id);
        $salaryDetail->update($request->all());

        return response()->json(['message' => 'Cập nhật chi tiết lương thành công!', 'salaryDetail' => $salaryDetail]);
    }

    // Xóa chi tiết lương
    public function destroy($id)
    {
        $salaryDetail = SalaryDetail::findOrFail($id);
        $salaryDetail->delete();

        return response()->json(['message' => 'Xóa chi tiết lương thành công!']);
    }
}