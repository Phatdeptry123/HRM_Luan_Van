<template>
  <div class="p-6 mx-auto bg-white rounded-xl shadow-md space-y-4 w-100">
    <div class="flex items-center space-x-4 w-100">
      <img class="h-50 w-100 rounded-full" src="../../assets/slide.png" alt="Avatar" />
      <div>
        <h1 class="text-4xl font-bold mt-10">Hồ sơ nhân sự</h1>
        <div class="text-xl font-medium text-black">{{ user.name }}</div>

        <!-- Phần lương có thể ẩn/hiện -->
        <p class="text-gray-500">
          <i class="fas fa-briefcase"></i>
          Lương:
          <span v-if="showSalary" class="mr-1">{{ user.salaries[0].total_salary }} $</span>
          <span v-else class="mr-1">*************</span>
          <!-- Icon để ẩn/hiện mức lương -->
          <font-awesome-icon
            class="cursor-pointer text-blue-500"
            :icon="showSalary ? 'eye-slash' : 'eye'"
            @click="toggleSalary"
          />
        </p>

        <p class="text-gray-500"><i class="fas fa-clock"></i> Chức vụ: {{ user.duty }}</p>
        <p class="text-gray-500">
          <i class="fas fa-envelope"></i> Số ngày onboard: {{ daysOnboard }} ngày
          <font-awesome-icon class="text-rose-500" :icon="['fas', 'heart']" />
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useUserStore } from '@/stores/user'
import { computed, ref } from 'vue'

const user = useUserStore().user

// Tính số ngày onboard
const daysOnboard = computed(() => {
  const currentDate = new Date()
  const createdDate = new Date(user.created_at)
  const diffTime = Math.abs(currentDate - createdDate)
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
})

// Trạng thái ẩn/hiển thị mức lương
const showSalary = ref(false)

const toggleSalary = () => {
  showSalary.value = !showSalary.value
}
</script>

<style scoped></style>
