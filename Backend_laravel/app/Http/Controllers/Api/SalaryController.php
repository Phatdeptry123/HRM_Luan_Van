<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Hiển thị danh sách lương.
     */
    public function index()
    {
        $salaries = Salary::with('user')->get();
        return response()->json($salaries);
    }

    /**
     * Lưu lương mới.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'social_insurance' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'deduction_description' => 'nullable|string',
            'total_salary' => 'required|numeric',
            'salary_date' => 'required|date',
        ]);

        $salary = Salary::create($validatedData);

        return response()->json($salary, 201);
    }

    /**
     * Cập nhật lương.
     */
    public function update(Request $request, Salary $salary)
    {
        $validatedData = $request->validate([
            'basic_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'social_insurance' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'deduction_description' => 'nullable|string',
            'total_salary' => 'required|numeric',
            'salary_date' => 'required|date',
        ]);

        $salary->update($validatedData);

        return response()->json($salary);
    }

    /**
     * Xóa lương.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();

        return response()->json(null, 204);
    }
}
