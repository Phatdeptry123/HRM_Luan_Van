<template>
  <div class="max-w-4xl mx-auto p-6 bg-base-100 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Mức Lương</h1>
    <div class="mb-4">
      <h2 class="text-lg font-semibold">{{ user.name }}</h2>
      <p>09.24_BL VP HOPEE_Monthly - 01/09/2024 - 30/09/2024</p>
    </div>

    <div class="flex justify-between items-center mb-4">
      <button
        @click="isVisibleUpdateSalaryModal = true"
        class="bg-blue-500 text-white py-2 px-4 rounded"
        @close="isVisibleUpdateSalaryModal = false"
      >
        Điều chỉnh lương cơ bản
      </button>
      <button
        @click="isVisibleHistorySalaryModal = true"
        class="bg-blue-500 text-white py-2 px-4 rounded"
      >
        Lịch sử lương
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Tên thành phần lương -->
      <div class="card bg-base-200 p-4">
        <h3 class="text-xl font-semibold">Chức danh</h3>
        <p>{{ user.duty }}</p>
        <p class="text-sm text-gray-500">Miêu tả: Tên của chức danh</p>
      </div>

      <div class="card bg-base-200 p-4">
        <h3 class="text-xl font-semibold">Mức lương hiện tại</h3>
        <p>{{ Math.floor(salaryData.basic_salary).toLocaleString() }} vnđ</p>
        <p class="text-sm text-gray-500">Miêu tả: Mức lương hợp đồng hiện tại</p>
      </div>

      <div class="card bg-base-200 p-4">
        <h3 class="text-xl font-semibold">Số công thực tế tháng này</h3>
        <p>{{ totalWorkingDays }}</p>
        <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
      </div>

      <div class="card bg-base-200 p-4">
        <h3 class="text-xl font-semibold">Lương đóng BHXH</h3>
        <p>{{ Math.floor(salaryData.social_insurance).toLocaleString() }} vnđ</p>
        <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
      </div>

      <div class="card bg-base-200 p-4">
        <h3 class="text-xl font-semibold">Thuế TNCN</h3>
        <p>{{ Math.floor(salaryData.tax).toLocaleString() }} vnđ</p>
        <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
      </div>

      <div class="card bg-base-200 p-4">
        <h3 class="text-xl font-semibold">Lương thực nhận</h3>
        <p>{{ Math.floor(salaryData.total_salary).toLocaleString() }} vnđ</p>
        <p class="text-sm text-gray-500">Miêu tả: Không có miêu tả</p>
      </div>
    </div>
    <BaseBigModal v-if="isVisibleMonthlySalaryModal" @close="isVisibleMonthlySalaryModal = false">
      <MonthlySalaryModal
        :userId="userId"
        :salaryData="salaryData"
        :attendanceData="attendanceData"
        :user="user"
        :totalWorkingDays="totalWorkingDays"
        :overtimeHours="overtimeHours"
        @closeMonthlySalaryModal="isVisibleMonthlySalaryModal = false"
      />
    </BaseBigModal>
    <BaseBigModal v-if="isVisibleHistorySalaryModal" @close="isVisibleHistorySalaryModal = false">
      <HistorySalaryModal :userId="userId" @close="isVisibleHistorySalaryModal = false" />
    </BaseBigModal>

    <BaseBigModal v-if="isVisibleUpdateSalaryModal" @close="isVisibleUpdateSalaryModal = false">
      <UpdateSalaryModal
        :userId="userId"
        :user="user"
        :salaryData="salaryData ?? {}"
        @close="isVisibleUpdateSalaryModal = false"
      />
    </BaseBigModal>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import BaseBigModal from '@/components/modal/BaseBigModal.vue'
import MonthlySalaryModal from '@/components/modal/salary/MonthlySalaryModal.vue'
import HistorySalaryModal from '@/components/modal/salary/HistorySalaryModal.vue'
import UpdateSalaryModal from '@/components/modal/salary/UpdateSalaryModal.vue'
import SalaryService from '@/services/salary.service'
import AttendanceService from '@/services/attendance.service'
import OvertimeService from '@/services/overtime.service'

const route = useRoute()
const userId = route.params.id
const salaryData = ref({
  basic_salary: 0,
  created_at: '',
  deduction_description: '',
  deductions: 0,
  id: 0,
  social_insurance: 0,
  tax: 0,
  total_salary: 0
})

const user = ref({})
const attendanceData = ref([])
const currentMonth = ref(new Date().getMonth() + 1)
const isVisibleHistorySalaryModal = ref(false)
const isVisibleUpdateSalaryModal = ref(false)
const actualWorkingDays = computed(() => {
  return attendanceData.value.filter((attendance) => {
    return (
      new Date(attendance.date).getMonth() + 1 === currentMonth.value &&
      attendance.check_in !== null &&
      attendance.check_out !== null
    )
  }).length
})

const countWeekendDays = () => {
  let count = 0
  for (let i = 1; i <= new Date(new Date().getFullYear(), currentMonth.value, 0).getDate(); i++) {
    const date = new Date(new Date().getFullYear(), currentMonth.value - 1, i)
    if (date.getDay() === 0 || date.getDay() === 6) {
      count++
    }
  }
  return count
}

const totalWorkingDays = computed(() => {
  return actualWorkingDays.value + countWeekendDays()
})

const isVisibleMonthlySalaryModal = ref(false)
const overtimeList = ref([])
const overtimeHours = computed(() => {
  // Lọc các phần tử có "status": "approved" và tính tổng "request_hour"
  return overtimeList.value
    .filter((item) => item.status === 'approved')
    .reduce((total, item) => total + item.request_hour, 0)
})

const fetchOvertimeList = async (userId) => {
  try {
    const overtimeData = await OvertimeService.getOvertimeListByUserId(userId)

    return overtimeData
  } catch (error) {
    console.error('Lỗi khi lấy dữ liệu overtime:', error)
    return []
  }
}

onMounted(async () => {
  try {
    // Gọi API để lấy thông tin lương từ SalaryService
    const salary = await SalaryService.getSalaryByUserId(userId)
    salaryData.value = salary
    user.value = salary.user

    // Gọi API để lấy thông tin số công từ AttendanceService
    const attendance = await AttendanceService.getAttendanceByUserId(userId)
    attendanceData.value = attendance

    // Gọi API để lấy danh sách overtime
    const overtimeData = await fetchOvertimeList(userId)
    overtimeList.value = overtimeData

    // Tính tổng số giờ overtime có trạng thái "approved"
    overtimeHours.value = overtimeData
      .filter((item) => item.status === 'approved')
      .reduce((total, item) => total + item.request_hour, 0)
  } catch (error) {
    console.error('Lỗi khi lấy dữ liệu từ API:', error)
  }
})
</script>

<style scoped>
/* Bổ sung các style tùy chỉnh nếu cần */
</style>
