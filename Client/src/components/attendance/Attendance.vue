<template>
  <div class="p-4">
    <div class="flex justify-between items-center mb-4">
      <!-- Date Navigation -->
      <div class="flex items-center space-x-4">
        <button @click="goToPreviousMonth" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded">
          Tháng trước
        </button>
      </div>
      <div class="text-lg font-semibold text-center flex-grow">
        {{ userName }}
        <span class="ml-2 text-sm text-gray-500">Chấm công {{ startDate }} - {{ endDate }}</span>
      </div>
      <div class="flex items-center space-x-4">
        <button @click="goToNextMonth" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded">
          Tháng sau
        </button>
      </div>
    </div>

    <!-- Timekeeping Table -->
    <div class="overflow-x-auto">
      <table class="table w-full border-collapse border-spacing-0 text-center">
        <thead>
          <tr>
            <th class="p-2 bg-gray-100">THỨ 2</th>
            <th class="p-2 bg-gray-100">THỨ 3</th>
            <th class="p-2 bg-gray-100">THỨ 4</th>
            <th class="p-2 bg-gray-100">THỨ 5</th>
            <th class="p-2 bg-gray-100">THỨ 6</th>
            <th class="p-2 bg-gray-100">THỨ 7</th>
            <th class="p-2 bg-gray-100">CHỦ NHẬT</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="week in weeksInMonth" :key="week">
            <td v-for="day in week" :key="day.date" class="border p-2">
              <div>{{ day.date || '' }}</div>
              <div v-if="day.data">Vào: {{ day.data.check_in }}</div>
              <div v-if="day.data">Ra: {{ day.data.check_out }}</div>
            </td>
          </tr>
        </tbody>
      </table>

      <button
        @click="$emit('closeCheckinHistory')"
        class="mt-4 bg-blue-500 text-white py-2 px-4 rounded"
      >
        Đóng
      </button>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import userService from '@/services/user.service'
import { useUserStore } from '@/stores/user'

const userId = useUserStore().user?.id

const currentYear = ref(new Date().getFullYear())
const currentMonth = ref(new Date().getMonth() + 1) // Tháng hiện tại
const startDate = ref('')
const endDate = ref('')

// State
const attendances = ref([])
const userName = ref('')
const weeksInMonth = ref([])

// Hàm cập nhật ngày đầu tiên và ngày cuối cùng của tháng
const updateDateRange = () => {
  startDate.value = `01/${currentMonth.value}/${currentYear.value}`
  endDate.value = `${new Date(currentYear.value, currentMonth.value, 0).getDate()}/${currentMonth.value}/${currentYear.value}`
}

// Hàm lấy số ngày trong tháng hiện tại
const getDaysInMonth = (year, month) => {
  const days = []
  const lastDayOfMonth = new Date(Date.UTC(year, month, 0)).getUTCDate()

  for (let day = 1; day <= lastDayOfMonth; day++) {
    days.push(new Date(Date.UTC(year, month - 1, day)))
  }

  return days
}

// Hàm xử lý hiển thị dữ liệu vào bảng
const createWeeksInMonth = (attendances) => {
  const daysInMonth = getDaysInMonth(currentYear.value, currentMonth.value)

  const weeks = []
  let week = []

  const firstDayOfWeek = daysInMonth[0].getDay()
  const emptyDays = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1

  for (let i = 0; i < emptyDays; i++) {
    week.push({ date: '', data: null })
  }

  daysInMonth.forEach((day) => {
    const formattedDate = day.toISOString().split('T')[0]
    const dayAttendance = attendances.find((a) => a.date === formattedDate)

    week.push({
      date: day.getDate(),
      data: dayAttendance || null
    })

    if (week.length === 7) {
      weeks.push(week)
      week = []
    }
  })

  if (week.length) {
    while (week.length < 7) {
      week.push({ date: '', data: null })
    }
    weeks.push(week)
  }

  weeksInMonth.value = weeks
}

// Gọi API khi component mounted
const fetchAttendanceData = async () => {
  try {
    const response = await userService.getCheckinHistory(userId)
    attendances.value = response
    userName.value = response[0]?.user.name
    createWeeksInMonth(attendances.value)
  } catch (error) {
    console.error('Lỗi khi lấy dữ liệu chấm công:', error)
  }
}

// Điều hướng sang tháng trước
const goToPreviousMonth = () => {
  if (currentMonth.value === 1) {
    currentMonth.value = 12
    currentYear.value -= 1
  } else {
    currentMonth.value -= 1
  }
  updateDateRange()
  fetchAttendanceData()
}

// Điều hướng sang tháng sau
const goToNextMonth = () => {
  if (currentMonth.value === 12) {
    currentMonth.value = 1
    currentYear.value += 1
  } else {
    currentMonth.value += 1
  }
  updateDateRange()
  fetchAttendanceData()
}

// Khởi tạo khi component mount
onMounted(() => {
  updateDateRange()
  fetchAttendanceData()
})
</script>
