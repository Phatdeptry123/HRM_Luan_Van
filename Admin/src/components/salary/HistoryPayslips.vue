<template>
  <div class="salary-fetcher">
    <h2>Get Monthly Salaries</h2>
    <form @submit.prevent="fetchSalaries">
      <label for="month">Tháng (YYYY-MM):</label>
      <input type="month" v-model="month" id="month" required class="border p-2 mb-4" />
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Fetch Salaries</button>
    </form>

    <div class="flex justify-between mt-4">
      <button @click="previousMonth" class="bg-gray-300 px-2 py-1 rounded">Tháng Trước</button>
      <button @click="nextMonth" class="bg-gray-300 px-2 py-1 rounded">Tháng sau</button>
    </div>

    <div v-if="error" class="text-red-500 mt-4">{{ error }}</div>

    <div v-if="salaries.length" class="mt-4">
      <h3>Salary Data for {{ month }}</h3>
      <table class="min-w-full table-auto border">
        <thead>
          <tr>
            <th class="border px-4 py-2">Nhân viên</th>
            <th class="border px-4 py-2">Lương cơ bản</th>
            <th class="border px-4 py-2">Số ngày công</th>
            <th class="border px-4 py-2">Số giờ tăng ca</th>
            <th class="border px-4 py-2">Lương tăng ca</th>
            <th class="border px-4 py-2">Thưởng</th>
            <th class="border px-4 py-2">Khấu trừ</th>
            <th class="border px-4 py-2">Thuế TNCN</th>
            <th class="border px-4 py-2">Lương đóng BHXH</th>
            <th class="border px-4 py-2">Thực nhận</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="salary in salaries" :key="salary.id">
            <td class="border px-4 py-2">{{ salary.user.name }}</td>
            <td class="border px-4 py-2">{{ formatCurrency(salary.basic_salary) }}</td>
            <td class="border px-4 py-2">{{ salary.working_days }}</td>
            <td class="border px-4 py-2">{{ salary.overtime_hours }}</td>
            <td class="border px-4 py-2">{{ formatCurrency(salary.overtime_salary) }}</td>
            <td class="border px-4 py-2">{{ formatCurrency(salary.bonus) }}</td>
            <td class="border px-4 py-2">{{ formatCurrency(salary.reduction) }}</td>
            <td class="border px-4 py-2">{{ formatCurrency(salary.tax) }}</td>
            <td class="border px-4 py-2">{{ formatCurrency(salary.social_insurance) }}</td>
            <td class="border px-4 py-2">{{ formatCurrency(salary.total_salary) }}</td>
          </tr>
        </tbody>
      </table>
      <button
        @click="exportToExcel"
        class="bg-green-500 text-white px-4 py-2 rounded mb-4 mt-5 float-end"
      >
        Export to Excel
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import SalaryService from '@/services/salary.service'
import * as XLSX from 'xlsx'

const month = ref('')
const salaries = ref([])
const error = ref(null)

// Hàm lấy dữ liệu lương
const fetchSalaries = async () => {
  error.value = null
  try {
    const response = await SalaryService.getMonthlySalaryByMonthAndYear(month.value)
    salaries.value = response
  } catch (err) {
    error.value = err.response?.data?.error || 'Failed to fetch salaries'
  }
}

// Hàm định dạng tiền tệ
const formatCurrency = (value) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(value)
}

// Hàm di chuyển đến tháng trước
const previousMonth = () => {
  const currentDate = new Date(month.value + '-01') // Chuyển đổi chuỗi tháng thành ngày
  currentDate.setMonth(currentDate.getMonth() - 1) // Giảm một tháng
  month.value = currentDate.toISOString().slice(0, 7) // Chuyển đổi lại thành định dạng YYYY-MM
  fetchSalaries() // Gọi lại hàm lấy dữ liệu
}

// Hàm di chuyển đến tháng sau
const nextMonth = () => {
  const currentDate = new Date(month.value + '-01')
  currentDate.setMonth(currentDate.getMonth() + 1) // Tăng một tháng
  month.value = currentDate.toISOString().slice(0, 7) // Chuyển đổi lại thành định dạng YYYY-MM
  fetchSalaries() // Gọi lại hàm lấy dữ liệu
}

// Hàm xuất dữ liệu ra file Excel
const exportToExcel = () => {
  // Tạo dữ liệu cho file Excel
  const worksheetData = salaries.value.map((salary) => ({
    User: salary.user.name,
    'Basic Salary': salary.basic_salary,
    'Working Days': salary.working_days,
    'Overtime Hours': salary.overtime_hours,
    'Overtime Salary': salary.overtime_salary,
    Bonus: salary.bonus,
    Reduction: salary.reduction,
    Tax: salary.tax,
    'Social Insurance': salary.social_insurance,
    'Total Salary': salary.total_salary
  }))

  // Tạo một worksheet từ dữ liệu
  const worksheet = XLSX.utils.json_to_sheet(worksheetData)

  // Tạo một workbook và thêm worksheet vào
  const workbook = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Salaries')

  // Xuất file Excel
  XLSX.writeFile(workbook, `SalaryData_${month.value}.xlsx`)
}

// Thiết lập tháng hiện tại khi component được mount
onMounted(() => {
  const currentDate = new Date()
  month.value = currentDate.toISOString().slice(0, 7) // Đặt tháng hiện tại
  fetchSalaries() // Gọi hàm lấy dữ liệu ngay khi mount
})
</script>

<style scoped></style>
