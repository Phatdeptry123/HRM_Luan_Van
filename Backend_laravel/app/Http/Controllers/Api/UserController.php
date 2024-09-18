<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Lấy tất cả người dùng và thông tin lương kèm theo
        $users = User::with('salaries')->get();

        // Trả về API response dạng JSON
        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'sometimes|string|min:8|confirmed',
            'duty' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'birthday' => 'required|date',
            'role' => 'required|string|max:255',
            'salary' => 'required|array',
            'salary.basic_salary' => 'required|numeric',
            'salary.bonus' => 'nullable|numeric',
            'salary.tax' => 'nullable|numeric',
            'salary.social_insurance' => 'nullable|numeric',
            'salary.deductions' => 'nullable|numeric',
            'salary.deduction_description' => 'nullable|string',
        ]);

        // Hàm loại bỏ dấu tiếng Việt
        function removeAccents($str)
        {
            $accents = [
                'a' => ['à', 'á', 'ả', 'ã', 'ạ', 'â', 'ầ', 'ấ', 'ẩ', 'ẫ', 'ậ', 'ă', 'ằ', 'ắ', 'ẳ', 'ẵ', 'ặ'],
                'e' => ['è', 'é', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ề', 'ế', 'ể', 'ễ', 'ệ'],
                'i' => ['ì', 'í', 'ỉ', 'ĩ', 'ị'],
                'o' => ['ò', 'ó', 'ỏ', 'õ', 'ọ', 'ô', 'ồ', 'ố', 'ổ', 'ỗ', 'ộ', 'ơ', 'ờ', 'ớ', 'ở', 'ỡ', 'ợ'],
                'u' => ['ù', 'ú', 'ủ', 'ũ', 'ụ', 'ư', 'ừ', 'ứ', 'ử', 'ữ', 'ự'],
                'y' => ['ỳ', 'ý', 'ỷ', 'ỹ', 'ỵ'],
                'd' => ['đ'],
                'A' => ['À', 'Á', 'Ả', 'Ã', 'Ạ', 'Â', 'Ầ', 'Ấ', 'Ẩ', 'Ẫ', 'Ậ', 'Ă', 'Ằ', 'Ắ', 'Ẳ', 'Ẵ', 'Ặ'],
                'E' => ['È', 'É', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ề', 'Ế', 'Ể', 'Ễ', 'Ệ'],
                'I' => ['Ì', 'Í', 'Ỉ', 'Ĩ', 'Ị'],
                'O' => ['Ò', 'Ó', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ồ', 'Ố', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ờ', 'Ớ', 'Ở', 'Ỡ', 'Ợ'],
                'U' => ['Ù', 'Ú', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ừ', 'Ứ', 'Ử', 'Ữ', 'Ự'],
                'Y' => ['Ỳ', 'Ý', 'Ỷ', 'Ỹ', 'Ỵ'],
                'D' => ['Đ'],
            ];

            foreach ($accents as $nonAccent => $accentChars) {
                $str = str_replace($accentChars, $nonAccent, $str);
            }

            return $str;
        }

        // Tách tên để tạo username
        $nameParts = explode(' ', $validatedData['name']);
        $lastName = removeAccents(array_pop($nameParts)); // Lấy tên và bỏ dấu
        $initials = '';

        // Lấy chữ cái đầu của họ và chữ lót (bỏ dấu)
        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr(removeAccents($part), 0, 1));
        }

        // Ghép tên và chữ cái đầu của họ và chữ lót
        $username = ucfirst($lastName) . $initials;

        // Kiểm tra xem username có bị trùng lặp không
        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        // Kiểm tra và gán mật khẩu mặc định nếu không có trong request
        $password = $validatedData['password'] ?? '123456';

        // Tạo mới người dùng
        try {
            // Tạo mới người dùng
            $user = User::create([
                'name' => $validatedData['name'],
                'username' => $username,
                'email' => $validatedData['email'],
                'password' => Hash::make($password),
                'duty' => $validatedData['duty'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'birthday' => $validatedData['birthday'],
                'role' => $validatedData['role'],
            ]);

            // Lấy dữ liệu lương từ validatedData
            $salaryData = $validatedData['salary'];
            $salaryData['user_id'] = $user->id; // Thêm user_id vào dữ liệu lương

            // Tính toán total_salary
            $totalSalary = $salaryData['basic_salary'] + ($salaryData['bonus'] ?? 0) -
                ($salaryData['tax'] ?? 0) -
                ($salaryData['social_insurance'] ?? 0) -
                ($salaryData['deductions'] ?? 0);
            $salaryData['total_salary'] = $totalSalary;

            // Xác định ngày lương, có thể là ngày hiện tại hoặc một ngày cụ thể
            $salaryData['salary_date'] = now(); // Ngày hiện tại

            // Tạo mới dữ liệu lương
            $salary = Salary::create($salaryData);

            // Trả về API response dạng JSON
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User created successfully.',
            ], 201);
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về API response dạng JSON
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        // Tìm người dùng theo ID và lấy thông tin lương kèm theo
        $user = User::with('salaries')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Trả về API response dạng JSON
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Explicitly fetch the user by ID
        $user = User::findOrFail($id);
        $salary = Salary::where('user_id', $user->id)->first();
    
        // Validate incoming data, only requiring fields that are present in the request
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                // Check for unique username but ignore the current user's username
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                // Check for unique email but ignore the current user's email
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|required|string|min:8|confirmed',
            'duty' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:15',
            'address' => 'sometimes|required|string|max:255',
            'birthday' => 'sometimes|required|date',
            'role' => 'sometimes|required|max:255',
            'salaries' => 'sometimes|required|array',
            'salaries.basic_salary' => 'sometimes|required|numeric',
            'salaries.bonus' => 'sometimes|nullable|numeric',
            'salaries.tax' => 'sometimes|nullable|numeric',
            'salaries.social_insurance' => 'sometimes|nullable|numeric',
            'salaries.deductions' => 'sometimes|nullable|numeric',
            'salaries.deduction_description' => 'sometimes|nullable|string',
        ]);
    
        // Update user information if the corresponding field is present in the request
        if ($request->filled('name')) {
            $user->name = $validatedData['name'];
        }
    
        if ($request->filled('username')) {
            $user->username = $validatedData['username'];
        }
    
        if ($request->filled('email')) {
            $user->email = $validatedData['email'];
        }
    
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }
    
        if ($request->filled('duty')) {
            $user->duty = $validatedData['duty'];
        }
    
        if ($request->filled('phone')) {
            $user->phone = $validatedData['phone'];
        }
    
        if ($request->filled('address')) {
            $user->address = $validatedData['address'];
        }
    
        if ($request->filled('birthday')) {
            $user->birthday = $validatedData['birthday'];
        }
    
        if ($request->filled('role')) {
            $user->role = $validatedData['role'];
        }
    
        if ($request->filled('salaries')) {
            $salaryData = $validatedData['salaries'];
    
            // Check if salary exists, if not, create a new Salary instance
            if (!$salary) {
                $salary = new Salary();
                $salary->user_id = $user->id;
            }
    
            if ($request->filled('salaries.basic_salary')) {
                $salary->basic_salary = $salaryData['basic_salary'];
            }
    
            if ($request->filled('salaries.bonus')) {
                $salary->bonus = $salaryData['bonus'];
            }
    
            if ($request->filled('salaries.tax')) {
                $salary->tax = $salaryData['tax'];
            }
    
            if ($request->filled('salaries.social_insurance')) {
                $salary->social_insurance = $salaryData['social_insurance'];
            }
    
            if ($request->filled('salaries.deductions')) {
                $salary->deductions = $salaryData['deductions'];
            }
    
            if ($request->filled('salaries.deduction_description')) {
                $salary->deduction_description = $salaryData['deduction_description'];
            }
    
            // Recalculate the total salary
            $totalSalary = $salaryData['basic_salary'] + ($salaryData['bonus'] ?? 0) -
                ($salaryData['tax'] ?? 0) -
                ($salaryData['social_insurance'] ?? 0) -
                ($salaryData['deductions'] ?? 0);
            $salary->total_salary = $totalSalary;
            $salary->salary_date = $salaryData['salary_date'] ?? now();
    
            // Save the updated salary information to the database
            $salary->save();
        }
    
        // Save the updated user information to the database
        $user->save();
    
        // Return a JSON response with the updated user information
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User updated successfully.',
        ]);
    }
    

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            // Tìm người dùng theo ID
            $user = User::findOrFail($id);

            // Xóa người dùng
            $user->delete();

            // Trả về API response dạng JSON
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về API response dạng JSON
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user.',
                'error' => $e->getMessage(),
            ], 500);
        }
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

    public function getManagerName($userId)
    {
        // Tìm user theo id
        $user = User::find($userId);

        // Nếu không tìm thấy user thì trả về lỗi 404
        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy user.'
            ], 404);
        }

        // Tìm người quản lý của user
        $manager = User::find($user->manager_id);

        // Nếu không tìm thấy người quản lý thì trả về lỗi 404
        if (!$manager) {
            return response()->json([
                'message' => 'Không tìm thấy người quản lý.'
            ], 404);
        }

        return response()->json([
            'manager_name' => $manager->name
        ]);
    }
}
