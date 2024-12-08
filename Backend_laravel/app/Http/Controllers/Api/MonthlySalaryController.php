<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\MonthlySalary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MonthlySalaryController extends Controller
{
    // Lấy danh sách lương theo tháng
    public function index()
    {
        try {
            $salaries = MonthlySalary::with('user')->get();
            return response()->json($salaries);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Lấy thông tin lương theo id
    public function show($id)
    {
        try {
            $salary = MonthlySalary::with('user')->findOrFail($id);
            return response()->json($salary);
        } catch (Exception $e) {
            return response()->json(['error' => 'Salary not found or ' . $e->getMessage()], 404);
        }
    }

    // Tạo mới bản ghi lương
    public function store(Request $request)
    {
        try {
            $salaries = $request->validate([
                '*.user_id' => 'required|exists:users,id',
                '*.basic_salary' => 'required|numeric',
                '*.bonus' => 'nullable|numeric',
                '*.bonus_description' => 'nullable|string',
                '*.reduction' => 'nullable|numeric',
                '*.reduction_description' => 'nullable|string',
                '*.tax' => 'nullable|numeric',
                '*.social_insurance' => 'nullable|numeric',
                '*.total_salary' => 'required|numeric',
                '*.month' => 'required|date_format:Y-m',
                '*.working_days' => 'nullable|integer',
                '*.overtime_hours' => 'nullable|integer',
                '*.overtime_salary' => 'nullable|numeric',
                '*.days_checkin_late_or_checkout_early' => 'nullable|integer',
                '*.deduction_checkin_late_or_checkout_early' => 'nullable|numeric',
            ]);

            $results = [];
            foreach ($salaries as $validatedData) {
                // Chuẩn hóa dữ liệu cho tháng và năm
                $validatedData['month'] = Carbon::createFromFormat('Y-m', $validatedData['month'])
                    ->startOfMonth()
                    ->setTime(0, 0, 0);

                // Kiểm tra xem lương tháng cho user_id và month đã tồn tại chưa
                $existingSalary = MonthlySalary::where('user_id', $validatedData['user_id'])
                    ->whereYear('month', $validatedData['month']->year)
                    ->whereMonth('month', $validatedData['month']->month)
                    ->first();

                // Nếu đã tồn tại, cập nhật bản ghi
                if ($existingSalary) {
                    $existingSalary->update($validatedData);
                    $results[] = $existingSalary;
                } else {
                    // Nếu không tồn tại, tạo mới
                    $salary = MonthlySalary::create($validatedData);
                    $results[] = $salary;
                }
            }

            return response()->json($results, 200);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Cập nhật bản ghi lương
    public function update(Request $request, $id)
    {
        try {
            $salary = MonthlySalary::findOrFail($id);

            $validatedData = $request->validate([
                'basic_salary' => 'required|numeric',
                'bonus' => 'nullable|numeric',
                'bonus_description' => 'nullable|string',
                'reduction' => 'nullable|numeric',
                'reduction_description' => 'nullable|string',
                'tax' => 'nullable|numeric',
                'social_insurance' => 'nullable|numeric',
                'total_salary' => 'required|numeric',
                'month' => 'required|date_format:Y-m',
                'working_days' => 'nullable|integer',
                'overtime_hours' => 'nullable|integer',
                'overtime_salary' => 'nullable|numeric',
                'days_checkin_late_or_checkout_early' => 'nullable|integer',
                'deduction_checkin_late_or_checkout_early' => 'nullable|numeric',
            ]);

            // Chuẩn hóa dữ liệu cho tháng và năm
            $validatedData['month'] = Carbon::createFromFormat('Y-m', $validatedData['month'])->startOfMonth();

            $salary->update($validatedData);
            return response()->json($salary);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Xoá bản ghi lương
    public function destroy($id)
    {
        try {
            $salary = MonthlySalary::findOrFail($id);
            $salary->delete();

            return response()->json(['message' => 'Deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Lấy thông tin lương theo id user
    public function getMonthlySalaryByUserId($id)
    {
        try {
            $salary = MonthlySalary::where('user_id', $id)
                ->with('user')
                ->orderBy('month', 'asc')
                ->get();
            return response()->json($salary);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // tạo lương hàng tháng cho 1 mảng user được truyền vào
    public function createMonthlySalaryForUsers(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'users' => 'required|array',
                'users.*' => 'required|exists:users,id',
                'basic_salary' => 'required|numeric',
                'bonus' => 'nullable|numeric',
                'bonus_description' => 'nullable|string',
                'reduction' => 'nullable|numeric',
                'reduction_description' => 'nullable|string',
                'tax' => 'nullable|numeric',
                'social_insurance' => 'nullable|numeric',
                'total_salary' => 'required|numeric',
                'month' => 'required|date_format:Y-m',
                'working_days' => 'nullable|integer',
                'overtime_hours' => 'nullable|integer',
                'overtime_salary' => 'nullable|numeric',
                'days_checkin_late_or_checkout_early' => 'nullable|integer',
                'deduction_checkin_late_or_checkout_early' => 'nullable|numeric',
            ]);

            // Chuẩn hóa dữ liệu cho tháng và năm
            $validatedData['month'] = Carbon::createFromFormat('Y-m', $validatedData['month'])
                ->startOfMonth()
                ->setTime(0, 0, 0);

            // Kiểm tra xem lương tháng cho user_id và month đã tồn tại chưa
            $existingSalaries = MonthlySalary::whereIn('user_id', $validatedData['users'])
                ->whereYear('month', $validatedData['month']->year)
                ->whereMonth('month', $validatedData['month']->month)
                ->get();

            // Nếu đã tồn tại, trả về lỗi
            if ($existingSalaries->isNotEmpty()) {
                return response()->json(['error' => 'The salary for this user and month already exists.'], 400);
            }

            $salaries = [];
            foreach ($validatedData['users'] as $userId) {
                $salary = MonthlySalary::create([
                    'user_id' => $userId,
                    'basic_salary' => $validatedData['basic_salary'],
                    'bonus' => $validatedData['bonus'],
                    'bonus_description' => $validatedData['bonus_description'],
                    'reduction' => $validatedData['reduction'],
                    'reduction_description' => $validatedData['reduction_description'],
                    'tax' => $validatedData['tax'],
                    'social_insurance' => $validatedData['social_insurance'],
                    'total_salary' => $validatedData['total_salary'],
                    'month' => $validatedData['month'],
                    'working_days' => $validatedData['working_days'],
                    'overtime_hours' => $validatedData['overtime_hours'],
                    'overtime_salary' => $validatedData['overtime_salary'],
                    'days_checkin_late_or_checkout_early' => $validatedData['days_checkin_late_or_checkout_early'],
                    'deduction_checkin_late_or_checkout_early' => $validatedData['deduction_checkin_late_or_checkout_early'],
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // lấy danh sách lương theo tháng và năm
    public function getMonthlySalaryByMonthAndYear(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'month' => 'required|date_format:Y-m',
            ]);

            $month = Carbon::createFromFormat('Y-m', $validatedData['month'])
                ->startOfMonth()
                ->setTime(0, 0, 0);

            $salaries = MonthlySalary::whereYear( $month->year)
                ->whereMonth('month', $month->month)
                ->with('user')
                ->get();

            return response()->json($salaries);
        } catch (Exception $e) {
            return response()->json(['month' => $month, 'error' => $e->getMessage(),

            ], 400);
        }
    }

    public function getAverageSalaryByMonthAndYear(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'month' => 'required|date_format:Y-m',
            ]);

            $month = Carbon::createFromFormat('Y-m', $validatedData['month'])
                ->startOfMonth()
                ->setTime(0, 0, 0);

            $averageSalary = MonthlySalary::where('month', 'like', $month->format('Y-m') . '%')
                ->avg('total_salary');
            
            return response()->json(['average_salary' => $averageSalary]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
