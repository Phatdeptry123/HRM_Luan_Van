<template>
  <div class="max-w-4xl mx-auto p-6 bg-base-100 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Phiếu lương tháng {{ currentMonth }}</h1>
    <div class="mb-4">
      <h2 class="text-lg font-semibold">{{ user.name }} - {{ user.username }} - {{ user.duty }}</h2>
    </div>

    <div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Mức lương hiện tại -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Mức lương hiện tại</h3>
          <input
            disabled
            v-model.number="salaryData.basic_salary"
            type="number"
            class="input input-bordered w-full"
          />
          <p class="text-sm text-gray-500">Miêu tả: Mức lương hợp đồng hiện tại</p>
        </div>

        <!-- Số công thực tế -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Số công thực tế tháng này</h3>
          <input
            disabled
            v-model.number="totalWorkingDays"
            type="number"
            class="input input-bordered w-full"
          />
          <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
        </div>

        <!-- TC nghỉ cuối tuần -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Số giờ tăng ca</h3>
          <input
            disabled
            v-model.number="overtimeHours"
            type="number"
            class="input input-bordered w-full"
          />
          <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
        </div>

        <!-- Lương tăng ca -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Lương tăng ca</h3>
          <input
            disabled
            v-model.number="salaryOvertime"
            type="number"
            class="input input-bordered w-full"
          />
          <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
        </div>

        <!-- Lương đóng BHXH -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Lương đóng BHXH</h3>
          <input
            disabled
            v-model.number="salaryData.social_insurance"
            type="number"
            class="input input-bordered w-full"
          />
          <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
        </div>

        <!-- Thuế TNCN -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Thuế TNCN</h3>
          <input
            disabled
            v-model.number="salaryData.tax"
            type="number"
            class="input input-bordered w-full"
          />
          <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
        </div>

        <!-- Khấu trừ -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Khấu trừ</h3>
          <input v-model.number="deductions" type="number" class="input input-bordered w-full" />

          <span class="text-sm text-gray-500">Miêu tả khấu trừ: </span>
          <textarea v-model="deductionsDescription" class="input input-bordered w-full" />
        </div>
        <!-- Thưởng tháng -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Tiền thưởng tháng</h3>
          <input v-model="bonus" type="number" class="input input-bordered w-full" />

          <span class="text-sm text-gray-500">Miêu tả tiền thưởng tháng: </span>
          <textarea v-model="bonusDescription" type="text" class="input input-bordered w-full" />
        </div>

        <!-- Tổng lương -->
        <div class="card bg-base-200 p-4">
          <h3 class="text-xl font-semibold">Tổng lương thực nhận</h3>
          <input
            disabled
            v-model.number="totalSalary"
            type="number"
            class="input input-bordered w-full text-4xl"
          />
          <p class="text-sm text-gray-500">Miêu tả: Tổng lương thực nhận</p>
        </div>
      </div>
      <div class="flex justify-between mt-4">
        <button
          class="bg-red-500 text-white py-2 px-4 rounded w-3/12 ml-20"
          @click="$emit('closeMonthlySalaryModal')"
        >
          Hủy
        </button>
        <button
          @click="storeMonthlySalary"
          class="bg-green-500 text-white py-2 mr-20 px-4 rounded w-3/12"
        >
          Lưu
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
const props = defineProps({
  userId: {
    type: String
  },
  user: {
    type: Object
  },
  salaryData: {
    type: Object
  },
  attendanceData: {
    type: Object
  },
  totalWorkingDays: {
    type: Number
  },
  overtimeHours: {
    type: Number
  }
})

const userId = ref(props.userId)
const user = ref(props.user)
const salaryData = ref(props.salaryData)
const attendanceData = ref(props.attendanceData)
const totalWorkingDays = ref(props.totalWorkingDays)
const overtimeHours = ref(props.overtimeHours)
const deductions = ref(0)
const deductionsDescription = ref('')
const bonus = ref(0)
const bonusDescription = ref('')
const currentMonth = ref(new Date().getMonth() + 1)
const countDaysInMonth = ref(
  new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate()
)

const salaryOvertime = computed(() => {
  return (salaryData.value.basic_salary / countDaysInMonth.value / 8) * overtimeHours.value * 1.5
})

onMounted(() => {
  console.log('userId', userId.value)
  console.log('user', user.value)
  console.log('salaryData', salaryData.value)
  console.log('attendanceData', attendanceData.value)
})

const totalSalary = computed(() => {
  const basicSalary = Number(salaryData.value.basic_salary) || 0
  const overtimeSalary = Number(salaryOvertime.value) || 0
  const socialInsurance = Number(salaryData.value.social_insurance) || 0
  const tax = Number(salaryData.value.tax) || 0
  const deductionsValue = Number(deductions.value) || 0
  const bonusValue = Number(bonus.value) || 0

  return basicSalary + overtimeSalary - socialInsurance - tax - deductionsValue + bonusValue
})

const storeMonthlySalary = async () => {
  const payload = {
    user_id: userId.value,
    basic_salary: salaryData.value.basic_salary,
    bonus: bonus.value,
    bonus_description: bonusDescription.value,
    reduction: deductions.value,
    reduction_description: deductionsDescription.value,
    tax: salaryData.value.tax,
    social_insurance: salaryData.value.social_insurance,
    total_salary: totalSalary.value,
    month: `${new Date().getFullYear()}-${String(currentMonth.value).padStart(2, '0')}`
  }

  try {
    const response = await axios.post('http://localhost:8000/api/monthly-salary', payload)
    console.log('Salary stored successfully:', response.data)
    // Thông báo thành công hoặc xử lý kết quả tại đây
  } catch (error) {
    console.error('Error storing salary:', error.response.data)
    // Thông báo lỗi hoặc xử lý lỗi tại đây
  }
}
</script>

<style scoped>
/* Add custom styles here */
</style>
