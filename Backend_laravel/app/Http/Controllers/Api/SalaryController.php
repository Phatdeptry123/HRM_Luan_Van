<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\Salary;
use App\Models\SalaryDetail;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function getSalaryByUserId($id)
    {
        $salaries = Salary::where('user_id', $id)->with('user')->get();
        return response()->json($salaries);
    }

    public function index()
    {
        $salaries = Salary::with(['user', 'user.attendances', 'user.requests', 'user.overtime'])->get();
        return response()->json($salaries);
    }

    public function creatOrUpdateBasicSalary(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $salary = Salary::where('user_id', $request->user_id)->first();
        if ($salary) {
            $salary->update($request->all());
        } else {
            $salary = Salary::create($request->all());
        }

        return response()->json($salary);
    }
}