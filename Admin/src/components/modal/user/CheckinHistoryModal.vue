<template>
  <div class="p-4">
    <div class="mb-4">
      <!-- Date Navigation -->
      <div class="flex justify-between">
        <button @click="goToPreviousMonth" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded">
          Tháng trước
        </button>
        <div class="text-lg font-semibold">
          {{ userName }}
          <span class="ml-2 text-sm text-gray-500">Chấm công {{ startDate }} - {{ endDate }}</span>
        </div>
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
            <td
              v-for="day in week"
              :key="day.date"
              class="border p-2"
              :class="{
                'bg-yellow-200': checkYellow(
                  day.data,
                  new Date(currentYear, currentMonth - 1, day.date).getDay()
                ),
                'bg-red-400': checkRed(
                  day.data,
                  new Date(currentYear, currentMonth - 1, day.date).getDay()
                )
              }"
            >
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

const props = defineProps({
  userId: {
    type: Number,
    required: true
  }
})

const currentYear = ref(new Date().getFullYear())
const currentMonth = ref(new Date().getMonth() + 1)
const startDate = ref('')
const endDate = ref('')

const attendances = ref([])
const userName = ref('')
const weeksInMonth = ref([])

const updateDateRange = () => {
  startDate.value = `01/${currentMonth.value}/${currentYear.value}`
  endDate.value = `${new Date(currentYear.value, currentMonth.value, 0).getDate()}/${currentMonth.value}/${currentYear.value}`
}

const getDaysInMonth = (year, month) => {
  const days = []
  const lastDayOfMonth = new Date(Date.UTC(year, month, 0)).getUTCDate()

  for (let day = 1; day <= lastDayOfMonth; day++) {
    days.push(new Date(Date.UTC(year, month - 1, day)))
  }

  return days
}

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

const fetchAttendanceData = async () => {
  try {
    const response = await userService.getCheckinHistory(props.userId)
    attendances.value = response
    userName.value = response[0]?.user.name
    createWeeksInMonth(attendances.value)
  } catch (error) {
    console.error('Lỗi khi lấy dữ liệu chấm công:', error)
  }
}

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

// Các hàm kiểm tra điều kiện
const checkYellow = (data, dayOfWeek) => {
  // Không tô màu cho thứ 7 (dayOfWeek === 6) và chủ nhật (dayOfWeek === 0)
  if (dayOfWeek === 6 || dayOfWeek === 0 || !data) return false

  const checkIn = data.check_in && data.check_in >= '08:00:01' && data.check_in <= '08:59:59'
  const checkOut = data.check_out && data.check_out >= '16:00:01' && data.check_out <= '16:59:59'
  return checkIn || checkOut
}

const checkRed = (data, dayOfWeek) => {
  console.log('data', data)

  // Không tô màu cho thứ 7 (dayOfWeek === 6) và chủ nhật (dayOfWeek === 0)
  if (dayOfWeek === 6 || dayOfWeek === 0) return false
  // Kiểm tra nếu không có dữ liệu hoặc ngày không thuộc tháng hiện tại
  if (!data || data.date.split('-')[1] !== String(currentMonth.value).padStart(2, '0')) return true
  // Kiểm tra giờ check_in và check_out
  if (data.check_in > '09:00:00') return true
  if (data.check_out < '17:00:00') return true

  return false
}

onMounted(() => {
  updateDateRange()
  fetchAttendanceData()
})
</script>
