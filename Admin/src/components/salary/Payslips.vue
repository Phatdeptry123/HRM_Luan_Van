<template>
  <div class="mx-auto p-6 bg-teal-50 rounded-lg shadow-lg">
    <div class="flex justify-between mt-6">
      <h2 class="text-2xl font-semibold mb-6">Cập Nhật Phiếu Lương</h2>
      <router-link to="/history-payslips" class="btn btn-primary"
        >Xem bảng lương hàng tháng</router-link
      >
    </div>
    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <!-- Table Header -->
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="p-3 text-left min-w-[200px]">Tên nhân viên</th>
            <th class="p-3 text-center min-w-[200px]">Lương cơ bản</th>
            <th class="p-3 text-center min-w-[200px]">Số công</th>
            <th class="p-3 text-center min-w-[200px]">Giờ tăng ca</th>
            <th class="p-3 text-center min-w-[200px]">Lương tăng ca</th>
            <th class="p-3 text-center min-w-[200px]">Lương BHXH</th>
            <th class="p-3 text-center min-w-[200px]">Thuế TNCN</th>
            <th class="p-3 text-center min-w-[200px]">Khấu trừ</th>
            <th class="p-3 text-center min-w-[200px]">Lý do khấu trừ</th>
            <th class="p-3 text-center min-w-[200px]">Thưởng</th>
            <th class="p-3 text-center min-w-[200px]">Lý do thưởng</th>
            <th class="p-3 text-center min-w-[200px]">Tổng lương</th>
          </tr>
        </thead>

        <!-- Table Body -->
        <tbody>
          <tr v-for="(salary, index) in salaries" :key="index" class="bg-gray-50 text-center">
            <td class="p-3 text-left font-medium text-gray-800 min-w-[200px]">
              {{ salary.user.name }}<br />
              <small class="text-gray-500"
                >{{ salary.user.username }} - {{ salary.user.duty }}</small
              >
            </td>

            <td class="p-3">
              <input
                v-model="salary.basic_salary"
                type="number"
                disabled
                class="input input-bordered w-full"
              />
            </td>
            <td class="p-3">
              <input
                v-model="salary.workingDays"
                type="number"
                disabled
                class="input input-bordered w-full"
              />
            </td>
            <td class="p-3">
              <input
                v-model="salary.overtimeHours"
                type="number"
                disabled
                class="input input-bordered w-full"
              />
            </td>
            <td class="p-3">
              <input
                v-model="salary.overtimeSalary"
                type="number"
                disabled
                class="input input-bordered w-full"
              />
            </td>
            <td class="p-3">
              <input
                v-model="salary.social_insurance"
                type="number"
                disabled
                class="input input-bordered w-full"
              />
            </td>
            <td class="p-3">
              <input
                v-model="salary.tax"
                type="number"
                disabled
                class="input input-bordered w-full"
              />
            </td>
            <td class="p-3">
              <input v-model="salary.deduction" type="number" class="input input-bordered w-full" />
            </td>
            <td class="p-3">
              <textarea
                v-model="salary.deduction_description"
                class="textarea textarea-bordered w-full"
              ></textarea>
            </td>
            <td class="p-3">
              <input v-model="salary.bonus" type="number" class="input input-bordered w-full" />
            </td>
            <td class="p-3">
              <textarea
                v-model="salary.bonus_description"
                class="textarea textarea-bordered w-full"
              ></textarea>
            </td>
            <td class="p-3 font-bold">
              <input
                disabled
                v-model="salary.totalSalary"
                type="number"
                class="input input-bordered w-full"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end mt-6">
      <BaseDarkButton @click="handleSave">
        Lưu tạo phiếu lương tháng {{ currentMonth }}
      </BaseDarkButton>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import Swal from 'sweetalert2'
import salaryService from '@/services/salary.service'
import router from '@/router'

const salaries = ref([
  {
    id: 0,
    user_id: 0,
    basic_salary: 0,
    bonus: 0,
    bonus_description: null,
    tax: 0,
    social_insurance: 0,
    deduction: 0,
    deduction_description: null,
    total_salary: 0,
    salary_date: null,
    created_at: null,
    updated_at: null,
    workingDays: 0,
    overtimeHours: 0,
    overtimeSalary: 0,
    totalSalary: 0,
    user: {
      id: 0,
      name: '',
      username: '',
      email: '',
      email_verified_at: null,
      duty: '',
      phone: '',
      address: '',
      birthday: '',
      role: 2,
      manager_id: 2,
      created_at: '',
      updated_at: '',
      avatar_img_url: null,
      face_img_url: null,
      attendances: [
        {
          id: 0,
          user_id: 0,
          date: '',
          check_in: '',
          check_out: '',
          status: '',
          notes: null,
          created_at: '',
          updated_at: ''
        },
        {
          id: 0,
          user_id: 0,
          date: '',
          check_in: '',
          check_out: '',
          status: '',
          notes: null,
          created_at: '',
          updated_at: ''
        }
      ],
      requests: [
        {
          id: 0,
          user_id: 0,
          type: '',
          description: '',
          request_date: '',
          status: '',
          manager_id: 1,
          created_at: '',
          updated_at: ''
        }
      ],
      overtime: [
        {
          id: 0,
          user_id: 0,
          manager_id: 0,
          description: '',
          request_hour: 0,
          request_date: '',
          status: '',
          created_at: '',
          updated_at: ''
        }
      ]
    }
  }
])

const currentMonth = new Date().getMonth() + 1

const workingDaysCalculation = (salary) => {
  salary.workingDays = salary.user.attendances.filter(
    (attendance) => attendance.created_at.split('-')[1] === currentMonth.toString()
  ).length
}

const overtimeHoursCalculation = (salary) => {
  salary.overtimeHours = salary.user.overtime
    .filter(
      (overtime) =>
        overtime.status === 'approved' &&
        overtime.created_at.split('-')[1] === currentMonth.toString()
    )
    .reduce((total, overtime) => total + overtime.request_hour, 0)
}

const countWorkingDays = (year, month) => {
  let count = 0
  const date = new Date(year, month - 1, 1)
  while (date.getMonth() === month - 1) {
    const day = date.getDay()
    if (day !== 0 && day !== 6) {
      // Exclude Sundays (0) and Saturdays (6)
      count++
    }
    date.setDate(date.getDate() + 1)
  }

  return count
}

const overtimeSalaryCalculation = (salary) => {
  salary.overtimeSalary = Math.round(
    salary.overtimeHours *
      ((salary.basic_salary / countWorkingDays(new Date().getFullYear(), currentMonth) / 8) * 1.5)
  )
}

const social_insuranceCalculation = (salary) => {
  salary.social_insurance = Math.round(salary.basic_salary * 0.23)
}

const taxCalculation = (salary) => {
  // 1. Xác định thu nhập tính thuế sau khi trừ bảo hiểm xã hội và các khoản giảm trừ
  const taxableIncome = salary.basic_salary + salary.overtimeSalary - salary.social_insurance

  // Nếu thu nhập sau khi giảm trừ nhỏ hơn 0 thì không tính thuế
  if (taxableIncome <= 0) {
    salary.tax = 0
    return
  }

  // 2. Tính thuế theo biểu thuế lũy tiến từng phần
  const calculateTax = (income) => {
    if (income <= 5000000) return income * 0.05
    if (income <= 10000000) return 5000000 * 0.05 + (income - 5000000) * 0.1
    if (income <= 18000000) return 5000000 * 0.05 + 5000000 * 0.1 + (income - 10000000) * 0.15
    if (income <= 32000000)
      return 5000000 * 0.05 + 5000000 * 0.1 + 8000000 * 0.15 + (income - 18000000) * 0.2
    if (income <= 52000000)
      return (
        5000000 * 0.05 +
        5000000 * 0.1 +
        8000000 * 0.15 +
        14000000 * 0.2 +
        (income - 32000000) * 0.25
      )
    if (income <= 80000000)
      return (
        5000000 * 0.05 +
        5000000 * 0.1 +
        8000000 * 0.15 +
        14000000 * 0.2 +
        20000000 * 0.25 +
        (income - 52000000) * 0.3
      )
    return (
      5000000 * 0.05 +
      5000000 * 0.1 +
      8000000 * 0.15 +
      14000000 * 0.2 +
      20000000 * 0.25 +
      28000000 * 0.3 +
      (income - 80000000) * 0.35
    )
  }

  // 3. Làm tròn số thuế phải nộp
  salary.tax = Math.round(calculateTax(taxableIncome))
}

const totalSalaryCalculation = (salary) => {
  salary.totalSalary =
    (Number(salary.basic_salary) || 0) +
    (Number(salary.overtimeSalary) || 0) -
    (Number(salary.social_insurance) || 0) -
    (Number(salary.tax) || 0) +
    (Number(salary.bonus) || 0) -
    (Number(salary.deduction) || 0)
}

const handleSave = async () => {
  try {
    await Promise.all(
      salaries.value.map((salary) =>
        salaryService.createMonthlySalary({
          user_id: salary.user.id,
          basic_salary: salary.basic_salary,
          bonus: salary.bonus,
          bonus_description: salary.bonus_description,
          tax: salary.tax,
          social_insurance: salary.social_insurance,
          deduction: salary.deduction,
          deduction_description: salary.deduction_description,
          total_salary: salary.totalSalary,
          month: `${new Date().getFullYear()}-${String(currentMonth).padStart(2, '0')}`,
          working_days: salary.workingDays,
          overtime_hours: salary.overtimeHours,
          overtime_salary: salary.overtimeSalary
        })
      )
    )
    Swal.fire('Thành công', 'Cập nhật phiếu lương thành công', 'success')
    router.push('/history-payslips')
  } catch (error) {
    console.error('Lỗi khi cập nhật phiếu lương:', error)
    Swal.fire('Lỗi', 'Có lỗi xảy ra khi cập nhật phiếu lương', 'error')
  }
}

watch(
  salaries,
  (newSalaries) => {
    newSalaries.forEach((salary) => {
      // Tính toán lại tổng lương
      totalSalaryCalculation(salary)
      console.log('cccccc')
    })
  },
  { deep: true }
)

onMounted(async () => {
  try {
    // Gọi API để lấy danh sách nhân viên
    const response = await salaryService.getAllSalaries()

    // Gán giá trị mặc định cho các trường không tồn tại
    salaries.value = response.map((salary) => ({
      ...salary,
      basic_salary: salary.basic_salary ?? 0,
      bonus: salary.bonus ?? 0,
      deduction: salary.deduction ?? 0,
      overtimeSalary: salary.overtimeSalary ?? 0,
      social_insurance: salary.social_insurance ?? 0,
      tax: salary.tax ?? 0,
      workingDays: salary.workingDays ?? 0,
      overtimeHours: salary.overtimeHours ?? 0,
      // Gán giá trị mặc định cho các trường con của 'user' nếu cần
      user: {
        ...salary.user,
        attendances: salary.user?.attendances ?? [],
        overtime: salary.user?.overtime ?? []
      }
    }))

    // Tính toán các giá trị cần thiết
    salaries.value.forEach((salary) => {
      workingDaysCalculation(salary)
      overtimeHoursCalculation(salary)
      overtimeSalaryCalculation(salary)
      social_insuranceCalculation(salary)
      taxCalculation(salary)
      totalSalaryCalculation(salary)
    })
  } catch (error) {
    console.error('Lỗi khi lấy danh sách nhân viên:', error)
  }
})
</script>

<style scoped>
/* Thêm style tùy chỉnh nếu cần */
.col-name {
  width: 150px;
}
.col-working-days,
.col-overtime-hours,
.col-overtime-salary,
.col-insurance-salary,
.col-income-tax,
.col-deductions,
.col-bonus,
.col-basic-salary,
.col-total-salary {
  width: 120px;
}
.col-deductions-description,
.col-bonus-description {
  width: 200px;
}

input[disabled],
textarea[disabled] {
  opacity: 1 !important;
  color: #000000 !important;
  background-color: #ffffff; /* Giữ màu nền nếu cần */
  cursor: not-allowed; /* Giữ trạng thái con trỏ */
}
</style>
