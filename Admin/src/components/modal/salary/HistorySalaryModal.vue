<template>
  <div class="monthly-salary p-6 bg-teal-50 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4 text-center">Lương hàng tháng của {{ currentUser.name }}</h2>
    <button class="btn btn-primary mb-4" @click="$emit('close')">Đóng</button>

    <div v-if="salaryList.length > 0">
      <ul class="space-y-4">
        <li
          v-for="salary in salaryList"
          :key="salary.id"
          class="border border-gray-300 p-4 rounded-lg bg-gray-50"
        >
          <h3 class="font-semibold text-lg">Đợt lương: {{ salary.month.slice(0, 7) }}</h3>
          <p class="text-gray-700">
            Cơ bản: <span class="font-bold">{{ salary.basic_salary }} đ</span>
          </p>
          <p class="text-gray-700">
            Thưởng: <span class="font-bold">{{ salary.bonus }} đ</span>
          </p>
          <p class="text-gray-700">
            Giảm trừ: <span class="font-bold">{{ salary.reduction }} đ</span>
          </p>
          <p class="text-gray-700">
            Thuế: <span class="font-bold">{{ salary.tax }} đ</span>
          </p>
          <p class="text-gray-700">
            Bảo hiểm xã hội: <span class="font-bold">{{ salary.social_insurance }} đ</span>
          </p>
          <p class="text-gray-700">
            Tổng lương: <span class="font-bold text-red-600">{{ salary.total_salary }} đ</span>
          </p>
        </li>
      </ul>
    </div>

    <div v-else>
      <p class="text-gray-500 text-center">Không có dữ liệu cho tháng này.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { format } from 'date-fns'

const salaryList = ref([])
const currentUser = ref({})
const currentMonth = ref(new Date())
const props = defineProps({
  userId: {
    type: String
  }
})

const userId = ref(props.userId)
const fetchSalaryData = async (month) => {
  const monthStr = format(month, 'yyyy-MM-dd')
  try {
    const response = await axios.get(
      `http://localhost:8000/api/monthly-salary/${userId.value}/get-monthly-salary-by-user-id`,
      {
        params: { month: monthStr }
      }
    )
    salaryList.value = response.data
    if (response.data.length > 0) {
      currentUser.value = response.data[0].user // Lấy thông tin người dùng từ dữ liệu đầu tiên
    }
  } catch (error) {
    console.error('Error fetching salary data:', error)
  }
}

onMounted(() => {
  fetchSalaryData(currentMonth.value)
})
</script>

<style scoped>
.btn {
  @apply bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition;
}

.btn:disabled {
  @apply bg-gray-300 cursor-not-allowed;
}
</style>
